package main

import (
	"context"
	"encoding/json"
	"log"
	"os"
	"syscall"
	"time"

	"github.com/joho/godotenv"
	amqp "github.com/rabbitmq/amqp091-go"
)

// DiskSpaceMessage is a struct to represent the disk space message.
type DiskSpaceMessage struct {
	AvailableSpace uint64 `json:"available_space"`
	Action         string `json:"action"`
}

func main() {
	loadEnv()

	initLogging()

	interval := getTickerInterval()
	ticker := time.NewTicker(1 * interval)
	defer ticker.Stop()

	for range ticker.C {
		availableSpace, err := getAvailableDiskSpace("/")
		if err != nil {
			log.Printf("Failed to get disk space information: %v", err)
			continue
		}

		body, err := parseRabbitMqBody(availableSpace)
		if err != nil {
			log.Printf("Failed to parse RabbitMQ body: %v", err)
			continue
		}

		err = publishToRabbitMq(body)
		if err != nil {
			log.Printf("Failed to publish message to RabbitMQ: %v", err)
		}
	}
}

func loadEnv() {
	err := godotenv.Load()
	if err != nil {
		log.Fatalf("Failed to load environment variables: %v", err)
	}
}

func getTickerInterval() time.Duration {
	// Get the tick interval from the environment variable
	tickIntervalStr := os.Getenv("TICK_INTERVAL")
	if tickIntervalStr == "" {
			log.Fatal("TICK_INTERVAL environment variable is not set")
	}

	tickInterval, err := time.ParseDuration(tickIntervalStr)
	if err != nil {
			log.Fatalf("Failed to parse TICK_INTERVAL: %v", err)
	}

	return tickInterval
}

func initLogging() {
	logFile, err := os.OpenFile("disk_space.log", os.O_CREATE|os.O_APPEND|os.O_WRONLY, 0666)
	if err != nil {
		log.Fatalf("Failed to open log file: %v", err)
	}
	defer logFile.Close()
	log.SetOutput(logFile)
}

func getAvailableDiskSpace(path string) (uint64, error) {
	var stat syscall.Statfs_t
	err := syscall.Statfs(path, &stat)
	if err != nil {
		return 0, err
	}
	return stat.Bavail * uint64(stat.Bsize), nil
}

func parseRabbitMqBody(availableSpace uint64) ([]byte, error) {
	message := DiskSpaceMessage{
		AvailableSpace: availableSpace,
		Action:         "disk_space",
	}
	return json.Marshal(message)
}

func publishToRabbitMq(body []byte) error {
	amqpURI := os.Getenv("AMQP_URI")
	conn, err := amqp.Dial(amqpURI)
	if err != nil {
		return err
	}
	defer conn.Close()

	ch, err := conn.Channel()
	if err != nil {
		return err
	}
	defer ch.Close()

	queueName := "disk_space"
	_, err = ch.QueueDeclare(
		queueName,
		false,
		false,
		false,
		false,
		nil,
	)
	if err != nil {
		return err
	}

	timeout := 5 * time.Second
	ctx, cancel := context.WithTimeout(context.Background(), timeout)
	defer cancel()

	err = ch.PublishWithContext(
		ctx,
		"",
		queueName,
		false,
		false,
		amqp.Publishing{
			ContentType: "application/json",
			Body:        body,
		})

	return err
}

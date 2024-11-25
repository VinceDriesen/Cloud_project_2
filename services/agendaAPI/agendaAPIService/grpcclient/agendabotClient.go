package grpcclient

import (
	"context"
	"log"
	"time"

	pb "agendaAPIService/generated"

	"google.golang.org/grpc"
	"google.golang.org/grpc/credentials/insecure"
)

type AgendabotClient struct {
	client pb.AgendabotServiceClient
	conn  *grpc.ClientConn
}

func NewAgendabotClient(serverAddress string) (*AgendabotClient, error) {
	// Maak een nieuwe gRPC-clientverbinding
	conn, err := grpc.Dial(serverAddress, grpc.WithTransportCredentials(insecure.NewCredentials()))
	
	if err != nil {
		return nil, err
	}

	log.Printf("Verbonden met Agendabot server op %s", serverAddress)

	// Maak een nieuwe client van de gegenereerde service
	client := pb.NewAgendabotServiceClient(conn)

	return &AgendabotClient{
		client: client,
		conn:   conn,
	}, nil
}

func (c *AgendabotClient) Close() error {
	return c.conn.Close()
}

func (c *AgendabotClient) GetAppointments(userID int32, maxRecommendations int32) ([]*pb.Appointment, error) {
	ctx, cancel := context.WithTimeout(context.Background(), time.Second*10)
	defer cancel()

	// Maak een gRPC-request
	req := &pb.RecommendationRequest{
		UserId:             userID,
		MaxRecommendations: maxRecommendations,
	}

	// Doe de call naar de server
	resp, err := c.client.GetAppointments(ctx, req)
	if err != nil {
		return nil, err
	}

	return resp.Appointments, nil
}
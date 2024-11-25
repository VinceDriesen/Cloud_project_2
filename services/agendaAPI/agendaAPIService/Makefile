# Directories
PROTO_DIR = ../../agendaProto
OUT_DIR = ./generated
PROTO_FILE = $(PROTO_DIR)/recommendations.proto

# Tools
PROTOC_GEN_GO = protoc-gen-go
PROTOC_GEN_GO_GRPC = protoc-gen-go-grpc

# Protoc command for Go
PROTOC = protoc \
    --go_out=$(OUT_DIR) \
    --go-grpc_out=$(OUT_DIR) \
    --go_opt=paths=source_relative \
    --go-grpc_opt=paths=source_relative

# Targets
all: generate_proto

generate_proto:
	@echo "Genereren van gRPC bestanden voor Go..."
	$(PROTOC) -I$(PROTO_DIR) $(PROTO_FILE)
	@echo "gRPC bestanden gegenereerd in $(OUT_DIR)"

PROTO_DIR = ../../agendaProto
OUT_DIR = ./generated
PROTO_FILE = $(PROTO_DIR)/recommendations.proto
PYTHON_OUT_DIR = $(OUT_DIR)

PROTOC_GEN_PYTHON = python -m grpc_tools.protoc
PROTOC = $(PROTOC_GEN_PYTHON) --python_out=$(PYTHON_OUT_DIR) --grpc_python_out=$(PYTHON_OUT_DIR)

all: generate_proto

generate_proto:
	@echo "Genereren van gRPC bestanden van .proto..."
	$(PROTOC) -I$(PROTO_DIR) $(PROTO_FILE)
	@echo "gRPC bestanden gegenereerd in $(PYTHON_OUT_DIR)"

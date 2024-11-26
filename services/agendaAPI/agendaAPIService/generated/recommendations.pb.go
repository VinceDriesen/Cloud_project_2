// Code generated by protoc-gen-go. DO NOT EDIT.
// versions:
// 	protoc-gen-go v1.35.2
// 	protoc        v3.19.6
// source: recommendations.proto

package generated

import (
	protoreflect "google.golang.org/protobuf/reflect/protoreflect"
	protoimpl "google.golang.org/protobuf/runtime/protoimpl"
	reflect "reflect"
	sync "sync"
)

const (
	// Verify that this generated code is sufficiently up-to-date.
	_ = protoimpl.EnforceVersion(20 - protoimpl.MinVersion)
	// Verify that runtime/protoimpl is sufficiently up-to-date.
	_ = protoimpl.EnforceVersion(protoimpl.MaxVersion - 20)
)

type Doctor struct {
	state         protoimpl.MessageState
	sizeCache     protoimpl.SizeCache
	unknownFields protoimpl.UnknownFields

	Id int32 `protobuf:"varint,1,opt,name=id,proto3" json:"id,omitempty"`
}

func (x *Doctor) Reset() {
	*x = Doctor{}
	mi := &file_recommendations_proto_msgTypes[0]
	ms := protoimpl.X.MessageStateOf(protoimpl.Pointer(x))
	ms.StoreMessageInfo(mi)
}

func (x *Doctor) String() string {
	return protoimpl.X.MessageStringOf(x)
}

func (*Doctor) ProtoMessage() {}

func (x *Doctor) ProtoReflect() protoreflect.Message {
	mi := &file_recommendations_proto_msgTypes[0]
	if x != nil {
		ms := protoimpl.X.MessageStateOf(protoimpl.Pointer(x))
		if ms.LoadMessageInfo() == nil {
			ms.StoreMessageInfo(mi)
		}
		return ms
	}
	return mi.MessageOf(x)
}

// Deprecated: Use Doctor.ProtoReflect.Descriptor instead.
func (*Doctor) Descriptor() ([]byte, []int) {
	return file_recommendations_proto_rawDescGZIP(), []int{0}
}

func (x *Doctor) GetId() int32 {
	if x != nil {
		return x.Id
	}
	return 0
}

type Appointment struct {
	state         protoimpl.MessageState
	sizeCache     protoimpl.SizeCache
	unknownFields protoimpl.UnknownFields

	Id       int32  `protobuf:"varint,1,opt,name=id,proto3" json:"id,omitempty"`
	DoctorId int32  `protobuf:"varint,2,opt,name=doctor_id,json=doctorId,proto3" json:"doctor_id,omitempty"`
	Date     string `protobuf:"bytes,3,opt,name=date,proto3" json:"date,omitempty"`
}

func (x *Appointment) Reset() {
	*x = Appointment{}
	mi := &file_recommendations_proto_msgTypes[1]
	ms := protoimpl.X.MessageStateOf(protoimpl.Pointer(x))
	ms.StoreMessageInfo(mi)
}

func (x *Appointment) String() string {
	return protoimpl.X.MessageStringOf(x)
}

func (*Appointment) ProtoMessage() {}

func (x *Appointment) ProtoReflect() protoreflect.Message {
	mi := &file_recommendations_proto_msgTypes[1]
	if x != nil {
		ms := protoimpl.X.MessageStateOf(protoimpl.Pointer(x))
		if ms.LoadMessageInfo() == nil {
			ms.StoreMessageInfo(mi)
		}
		return ms
	}
	return mi.MessageOf(x)
}

// Deprecated: Use Appointment.ProtoReflect.Descriptor instead.
func (*Appointment) Descriptor() ([]byte, []int) {
	return file_recommendations_proto_rawDescGZIP(), []int{1}
}

func (x *Appointment) GetId() int32 {
	if x != nil {
		return x.Id
	}
	return 0
}

func (x *Appointment) GetDoctorId() int32 {
	if x != nil {
		return x.DoctorId
	}
	return 0
}

func (x *Appointment) GetDate() string {
	if x != nil {
		return x.Date
	}
	return ""
}

type RecommendationRequest struct {
	state         protoimpl.MessageState
	sizeCache     protoimpl.SizeCache
	unknownFields protoimpl.UnknownFields

	UserId             int32 `protobuf:"varint,1,opt,name=user_id,json=userId,proto3" json:"user_id,omitempty"`
	MaxRecommendations int32 `protobuf:"varint,2,opt,name=max_recommendations,json=maxRecommendations,proto3" json:"max_recommendations,omitempty"`
}

func (x *RecommendationRequest) Reset() {
	*x = RecommendationRequest{}
	mi := &file_recommendations_proto_msgTypes[2]
	ms := protoimpl.X.MessageStateOf(protoimpl.Pointer(x))
	ms.StoreMessageInfo(mi)
}

func (x *RecommendationRequest) String() string {
	return protoimpl.X.MessageStringOf(x)
}

func (*RecommendationRequest) ProtoMessage() {}

func (x *RecommendationRequest) ProtoReflect() protoreflect.Message {
	mi := &file_recommendations_proto_msgTypes[2]
	if x != nil {
		ms := protoimpl.X.MessageStateOf(protoimpl.Pointer(x))
		if ms.LoadMessageInfo() == nil {
			ms.StoreMessageInfo(mi)
		}
		return ms
	}
	return mi.MessageOf(x)
}

// Deprecated: Use RecommendationRequest.ProtoReflect.Descriptor instead.
func (*RecommendationRequest) Descriptor() ([]byte, []int) {
	return file_recommendations_proto_rawDescGZIP(), []int{2}
}

func (x *RecommendationRequest) GetUserId() int32 {
	if x != nil {
		return x.UserId
	}
	return 0
}

func (x *RecommendationRequest) GetMaxRecommendations() int32 {
	if x != nil {
		return x.MaxRecommendations
	}
	return 0
}

type RecommendationResponse struct {
	state         protoimpl.MessageState
	sizeCache     protoimpl.SizeCache
	unknownFields protoimpl.UnknownFields

	Appointments []*Appointment `protobuf:"bytes,1,rep,name=appointments,proto3" json:"appointments,omitempty"`
}

func (x *RecommendationResponse) Reset() {
	*x = RecommendationResponse{}
	mi := &file_recommendations_proto_msgTypes[3]
	ms := protoimpl.X.MessageStateOf(protoimpl.Pointer(x))
	ms.StoreMessageInfo(mi)
}

func (x *RecommendationResponse) String() string {
	return protoimpl.X.MessageStringOf(x)
}

func (*RecommendationResponse) ProtoMessage() {}

func (x *RecommendationResponse) ProtoReflect() protoreflect.Message {
	mi := &file_recommendations_proto_msgTypes[3]
	if x != nil {
		ms := protoimpl.X.MessageStateOf(protoimpl.Pointer(x))
		if ms.LoadMessageInfo() == nil {
			ms.StoreMessageInfo(mi)
		}
		return ms
	}
	return mi.MessageOf(x)
}

// Deprecated: Use RecommendationResponse.ProtoReflect.Descriptor instead.
func (*RecommendationResponse) Descriptor() ([]byte, []int) {
	return file_recommendations_proto_rawDescGZIP(), []int{3}
}

func (x *RecommendationResponse) GetAppointments() []*Appointment {
	if x != nil {
		return x.Appointments
	}
	return nil
}

var File_recommendations_proto protoreflect.FileDescriptor

var file_recommendations_proto_rawDesc = []byte{
	0x0a, 0x15, 0x72, 0x65, 0x63, 0x6f, 0x6d, 0x6d, 0x65, 0x6e, 0x64, 0x61, 0x74, 0x69, 0x6f, 0x6e,
	0x73, 0x2e, 0x70, 0x72, 0x6f, 0x74, 0x6f, 0x12, 0x09, 0x61, 0x67, 0x65, 0x6e, 0x64, 0x61, 0x62,
	0x6f, 0x74, 0x22, 0x18, 0x0a, 0x06, 0x44, 0x6f, 0x63, 0x74, 0x6f, 0x72, 0x12, 0x0e, 0x0a, 0x02,
	0x69, 0x64, 0x18, 0x01, 0x20, 0x01, 0x28, 0x05, 0x52, 0x02, 0x69, 0x64, 0x22, 0x4e, 0x0a, 0x0b,
	0x41, 0x70, 0x70, 0x6f, 0x69, 0x6e, 0x74, 0x6d, 0x65, 0x6e, 0x74, 0x12, 0x0e, 0x0a, 0x02, 0x69,
	0x64, 0x18, 0x01, 0x20, 0x01, 0x28, 0x05, 0x52, 0x02, 0x69, 0x64, 0x12, 0x1b, 0x0a, 0x09, 0x64,
	0x6f, 0x63, 0x74, 0x6f, 0x72, 0x5f, 0x69, 0x64, 0x18, 0x02, 0x20, 0x01, 0x28, 0x05, 0x52, 0x08,
	0x64, 0x6f, 0x63, 0x74, 0x6f, 0x72, 0x49, 0x64, 0x12, 0x12, 0x0a, 0x04, 0x64, 0x61, 0x74, 0x65,
	0x18, 0x03, 0x20, 0x01, 0x28, 0x09, 0x52, 0x04, 0x64, 0x61, 0x74, 0x65, 0x22, 0x61, 0x0a, 0x15,
	0x52, 0x65, 0x63, 0x6f, 0x6d, 0x6d, 0x65, 0x6e, 0x64, 0x61, 0x74, 0x69, 0x6f, 0x6e, 0x52, 0x65,
	0x71, 0x75, 0x65, 0x73, 0x74, 0x12, 0x17, 0x0a, 0x07, 0x75, 0x73, 0x65, 0x72, 0x5f, 0x69, 0x64,
	0x18, 0x01, 0x20, 0x01, 0x28, 0x05, 0x52, 0x06, 0x75, 0x73, 0x65, 0x72, 0x49, 0x64, 0x12, 0x2f,
	0x0a, 0x13, 0x6d, 0x61, 0x78, 0x5f, 0x72, 0x65, 0x63, 0x6f, 0x6d, 0x6d, 0x65, 0x6e, 0x64, 0x61,
	0x74, 0x69, 0x6f, 0x6e, 0x73, 0x18, 0x02, 0x20, 0x01, 0x28, 0x05, 0x52, 0x12, 0x6d, 0x61, 0x78,
	0x52, 0x65, 0x63, 0x6f, 0x6d, 0x6d, 0x65, 0x6e, 0x64, 0x61, 0x74, 0x69, 0x6f, 0x6e, 0x73, 0x22,
	0x54, 0x0a, 0x16, 0x52, 0x65, 0x63, 0x6f, 0x6d, 0x6d, 0x65, 0x6e, 0x64, 0x61, 0x74, 0x69, 0x6f,
	0x6e, 0x52, 0x65, 0x73, 0x70, 0x6f, 0x6e, 0x73, 0x65, 0x12, 0x3a, 0x0a, 0x0c, 0x61, 0x70, 0x70,
	0x6f, 0x69, 0x6e, 0x74, 0x6d, 0x65, 0x6e, 0x74, 0x73, 0x18, 0x01, 0x20, 0x03, 0x28, 0x0b, 0x32,
	0x16, 0x2e, 0x61, 0x67, 0x65, 0x6e, 0x64, 0x61, 0x62, 0x6f, 0x74, 0x2e, 0x41, 0x70, 0x70, 0x6f,
	0x69, 0x6e, 0x74, 0x6d, 0x65, 0x6e, 0x74, 0x52, 0x0c, 0x61, 0x70, 0x70, 0x6f, 0x69, 0x6e, 0x74,
	0x6d, 0x65, 0x6e, 0x74, 0x73, 0x32, 0x6a, 0x0a, 0x10, 0x41, 0x67, 0x65, 0x6e, 0x64, 0x61, 0x62,
	0x6f, 0x74, 0x53, 0x65, 0x72, 0x76, 0x69, 0x63, 0x65, 0x12, 0x56, 0x0a, 0x0f, 0x47, 0x65, 0x74,
	0x41, 0x70, 0x70, 0x6f, 0x69, 0x6e, 0x74, 0x6d, 0x65, 0x6e, 0x74, 0x73, 0x12, 0x20, 0x2e, 0x61,
	0x67, 0x65, 0x6e, 0x64, 0x61, 0x62, 0x6f, 0x74, 0x2e, 0x52, 0x65, 0x63, 0x6f, 0x6d, 0x6d, 0x65,
	0x6e, 0x64, 0x61, 0x74, 0x69, 0x6f, 0x6e, 0x52, 0x65, 0x71, 0x75, 0x65, 0x73, 0x74, 0x1a, 0x21,
	0x2e, 0x61, 0x67, 0x65, 0x6e, 0x64, 0x61, 0x62, 0x6f, 0x74, 0x2e, 0x52, 0x65, 0x63, 0x6f, 0x6d,
	0x6d, 0x65, 0x6e, 0x64, 0x61, 0x74, 0x69, 0x6f, 0x6e, 0x52, 0x65, 0x73, 0x70, 0x6f, 0x6e, 0x73,
	0x65, 0x42, 0x0d, 0x5a, 0x0b, 0x2e, 0x2f, 0x67, 0x65, 0x6e, 0x65, 0x72, 0x61, 0x74, 0x65, 0x64,
	0x62, 0x06, 0x70, 0x72, 0x6f, 0x74, 0x6f, 0x33,
}

var (
	file_recommendations_proto_rawDescOnce sync.Once
	file_recommendations_proto_rawDescData = file_recommendations_proto_rawDesc
)

func file_recommendations_proto_rawDescGZIP() []byte {
	file_recommendations_proto_rawDescOnce.Do(func() {
		file_recommendations_proto_rawDescData = protoimpl.X.CompressGZIP(file_recommendations_proto_rawDescData)
	})
	return file_recommendations_proto_rawDescData
}

var file_recommendations_proto_msgTypes = make([]protoimpl.MessageInfo, 4)
var file_recommendations_proto_goTypes = []any{
	(*Doctor)(nil),                 // 0: agendabot.Doctor
	(*Appointment)(nil),            // 1: agendabot.Appointment
	(*RecommendationRequest)(nil),  // 2: agendabot.RecommendationRequest
	(*RecommendationResponse)(nil), // 3: agendabot.RecommendationResponse
}
var file_recommendations_proto_depIdxs = []int32{
	1, // 0: agendabot.RecommendationResponse.appointments:type_name -> agendabot.Appointment
	2, // 1: agendabot.AgendabotService.GetAppointments:input_type -> agendabot.RecommendationRequest
	3, // 2: agendabot.AgendabotService.GetAppointments:output_type -> agendabot.RecommendationResponse
	2, // [2:3] is the sub-list for method output_type
	1, // [1:2] is the sub-list for method input_type
	1, // [1:1] is the sub-list for extension type_name
	1, // [1:1] is the sub-list for extension extendee
	0, // [0:1] is the sub-list for field type_name
}

func init() { file_recommendations_proto_init() }
func file_recommendations_proto_init() {
	if File_recommendations_proto != nil {
		return
	}
	type x struct{}
	out := protoimpl.TypeBuilder{
		File: protoimpl.DescBuilder{
			GoPackagePath: reflect.TypeOf(x{}).PkgPath(),
			RawDescriptor: file_recommendations_proto_rawDesc,
			NumEnums:      0,
			NumMessages:   4,
			NumExtensions: 0,
			NumServices:   1,
		},
		GoTypes:           file_recommendations_proto_goTypes,
		DependencyIndexes: file_recommendations_proto_depIdxs,
		MessageInfos:      file_recommendations_proto_msgTypes,
	}.Build()
	File_recommendations_proto = out.File
	file_recommendations_proto_rawDesc = nil
	file_recommendations_proto_goTypes = nil
	file_recommendations_proto_depIdxs = nil
}

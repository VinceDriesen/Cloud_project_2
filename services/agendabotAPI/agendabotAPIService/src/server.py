import grpc
from concurrent import futures
import logging
from src.services import get_doctor_agendas, get_free_appointments_for_doctor
from generated import recommendations_pb2, recommendations_pb2_grpc


class AgendabotService(recommendations_pb2_grpc.AgendabotServiceServicer):
    def GetAppointments(self, request, context):
        """
        Genereert aanbevolen afspraken op basis van beschikbare doktersagenda's.
        """
        try:
            logging.info(f"Request ontvangen voor user_id={request.user_id} met max_recommendations={request.max_recommendations}")
            user_id = request.user_id
            max_recommendations = request.max_recommendations

            doctor_agenda_ids = get_doctor_agendas()
            recommendations = []

            for doctor_agenda_id in doctor_agenda_ids:
                appointments = get_free_appointments_for_doctor(doctor_agenda_id)
                optimal_appointments = self.get_optimal_appointments(appointments, max_recommendations)
                recommendations.extend(optimal_appointments)

            print(recommendations)
            grpc_appointments = [
                recommendations_pb2.Appointment(
                    id=appt["id"],
                    doctor_id=doctor_agenda_id,
                    date=appt["date"],
                ) for appt in recommendations
            ]
            print("grpc_appointments", grpc_appointments)

            return recommendations_pb2.RecommendationResponse(appointments=grpc_appointments)
        except Exception as e:
            logging.error(f"Fout in GetAppointments: {e}")
            context.set_details(str(e))
            context.set_code(grpc.StatusCode.INTERNAL)
            return recommendations_pb2.RecommendationResponse()

    def get_optimal_appointments(self, appointments, max_recommendations):
        """
        Selecteert de optimale afspraken uit een lijst van beschikbare afspraken.
        """
        try:
            # Sorteer afspraken op datum
            # sorted_appointments = sorted(appointments, key=lambda x: x['date'])
            return appointments[:max_recommendations]
        except Exception as e:
            logging.error(f"Fout in get_optimal_appointments: {e}")
            raise

def serve():
    """
    Start de gRPC-server.
    """
    logging.basicConfig(level=logging.INFO)
    server = grpc.server(futures.ThreadPoolExecutor(max_workers=10))
    recommendations_pb2_grpc.add_AgendabotServiceServicer_to_server(AgendabotService(), server)

    server_address = '[::]:50051'
    logging.info(f"gRPC-server gestart op {server_address}")
    server.add_insecure_port(server_address)
    server.start()
    server.wait_for_termination()


if __name__ == '__main__':
    serve()

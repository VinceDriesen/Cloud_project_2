@extends('layouts.app')

@section('content')
<div class="w-fit mx-auto mt-20 min-h-[83.9vh]" x-data="optimalAppointments()">
    <div class="flex justify-between gap-10">
        {{-- Eerste formulier --}}
        <form
            id="appointment-form"
            class="w-fit flex flex-col gap-5 p-5 bg-foreground rounded-2xl"
            method="POST"
            x-data="appointmentForm()"
        >
            @csrf
            {{-- Stap 1: Selecteer een dokter --}}
            <div class="flex flex-col">
                <label for="doctor" class="text-primary-text-color text-lg">Selecteer een dokter:</label>
                <select
                    id="doctor"
                    name="doctor_id"
                    x-model="selectedDoctor"
                    @change="fetchAvailableData"
                    class="p-2 outline-none bg-component rounded-lg text-primary-text-color"
                    required
                >
                    <option value="" disabled selected>Selecteer een dokter</option>
                    @foreach($doctors as $doctor)
                        <option value="{{ $doctor->id }}">{{ $doctor->firstname }} {{ $doctor->lastname }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Stap 2: Selecteer een dag --}}
            <div class="flex flex-col mt-4">
                <label for="day" class="text-primary-text-color text-lg">Selecteer een dag:</label>
                <select
                    id="day"
                    name="appointment_day"
                    x-model="selectedDay"
                    class="p-2 outline-none bg-component rounded-lg text-primary-text-color"
                    :disabled="!availableDays.length"
                    required
                >
                    <option value="" disabled selected>Selecteer een dag</option>
                    <template x-for="day in availableDays" :key="day">
                        <option x-text="day" :value="day"></option>
                    </template>
                </select>
            </div>

            {{-- Stap 3: Selecteer een tijdstip --}}
            <div class="flex flex-col mt-4">
                <label for="appointment-time" class="text-primary-text-color text-lg">Selecteer een tijdstip:</label>
                <select
                    id="appointment-time"
                    name="appointment_time"
                    x-model="selectedTime"
                    @change="setAppointmentId"
                    class="p-2 outline-none bg-component rounded-lg text-primary-text-color"
                    :disabled="!availableTimes.length"
                    required
                >
                    <option value="" disabled selected>Selecteer een tijdstip</option>
                    <template x-for="time in availableTimes" :key="time.appointmentId">
                        <option
                            x-text="time.time"
                            :value="time.time"
                            :data-appointment-id="time.appointmentId"
                        ></option>
                    </template>
                </select>
            </div>

            {{-- Hidden input voor appointment ID --}}
            <input type="hidden" name="appointment_id" x-model="appointmentId">

            {{-- Submit knop --}}
            <div class="flex justify-end mt-5">
                <button
                    type="submit"
                    class="btn bg-action hover:bg-action-hover border-none text-white"
                    :disabled="!selectedDoctor || !selectedDay || !selectedTime"
                >Maak afspraak</button>
            </div>
        </form>

        {{-- Tweede formulier --}}
        <form
            id="optimal-appointments-form"
            class="w-fit flex flex-col gap-5 p-5 bg-foreground rounded-2xl"
            @submit.prevent="fetchOptimalAppointments"
        >
            <label for="appointment-count" class="text-primary-text-color text-lg">Hoeveel afspraken:</label>
            <input
                type="number"
                id="appointment-count"
                name="number_of_appointments"
                x-model="numberOfAppointments"
                class="p-2 outline-none bg-component rounded-lg text-primary-text-color"
                min="1"
                required
            >
            <div class="mt-4">
                <button
                    type="submit"
                    class="btn bg-action hover:bg-action-hover border-none text-white"
                >Find optimal appointments with 1 doctor</button>
            </div>
        </form>
    </div>

    {{-- Optimal Afspraken Resultaten --}}
    <div id="appointments" class="mt-10 w-full max-w-6xl">
        <h2 class="text-white text-2xl font-bold mb-5" x-show="appointments.length > 0">Maak Een Afspraak</h2>
        <div id="appointmentList" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" x-show="appointments.length > 0">
            <template x-for="appointment in appointments" :key="appointment.id">
                <form method="POST" class="bg-gray-800 text-white rounded-lg shadow-md p-6 flex flex-col">
                    @csrf
                    <input type="hidden" name="appointment_id" x-model="appointment.id">
                    <div class="mb-4">
                        <h3 class="text-xl font-bold" x-text="'Titel: ' + appointment.agendaItem.title"></h3>
                        <p class="text-gray-300" x-text="'Beschrijving: ' + appointment.agendaItem.description"></p>
                        <p class="text-gray-300" x-text="'Duur: ' + appointment.agendaItem.duration + ' minuten'"></p>
                        <p class="text-gray-300" x-text="'Datum: ' + appointment.agendaItem.date.day + '/' + appointment.agendaItem.date.month + '/' + appointment.agendaItem.date.year"></p>
                        <p class="text-gray-300" x-text="'Tijd: ' + appointment.agendaItem.date.hour + ':' + appointment.agendaItem.date.minute"></p>
                    </div>
                    <div class="flex justify-end">
                        <button
                            type="submit"
                            class="btn bg-action hover:bg-action-hover border-none text-white"
                        >Maak afspraak</button>
                    </div>
                </form>
            </template>
        </div>
    </div>
</div>
@endsection

<script>
    function appointmentForm() {
        return {
            selectedDoctor: '',
            selectedDay: '',
            selectedTime: '',
            appointmentId: '',
            availableDays: [],
            availableTimes: [],

            fetchAvailableData() {
                if (!this.selectedDoctor) {
                    this.availableDays = [];
                    this.availableTimes = [];
                    return;
                }

                fetch(`/appointments/doctor/${this.selectedDoctor}`)
                    .then(response => response.json())
                    .then(data => {
                        this.availableDays = data.availableDays || [];
                        this.availableTimes = data.availableTimes || [];
                        this.selectedDay = '';
                    })
                    .catch(error => console.error('Error fetching available data:', error));
            },

            setAppointmentId(event) {
                const selectedOption = event.target.selectedOptions[0];
                this.appointmentId = selectedOption.getAttribute('data-appointment-id');
            }
        }
    }

    function optimalAppointments() {
        return {
            numberOfAppointments: 1,
            appointments: [],
            fetchOptimalAppointments() {
                fetch(`/getOptimalAppointments?number_of_appointments=${this.numberOfAppointments}`)
                    .then(response => response.json())
                    .then(data => {
                        this.appointments = data.appointments || [];
                    })
                    .catch(error => console.error('Error fetching appointments:', error));
            }
        };
    }
</script>

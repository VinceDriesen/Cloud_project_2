@extends('layouts.app')

@section('content')
<div class="w-fit mx-auto mt-20 min-h-[83.9vh]">
    <form
        id="appointment-form"
        class="w-fit flex flex-col gap-5 p-5 bg-foreground rounded-2xl"
        method="POST"
        x-data="appointmentForm()"
    >
        @csrf

        {{-- Stap 1: Kies een dokter --}}
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

        {{-- Stap 2: Kies een dag --}}
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

        {{-- Stap 3: Kies een beschikbaar tijdstip --}}
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

        {{-- Hidden input for appointment ID --}}
        <input type="hidden" name="appointment_id" x-model="appointmentId"> <!-- Zorg dat x-model hier overeenkomt met de gebruikte naam -->

        {{-- Submit knop --}}
        <div class="flex justify-end mt-5">
            <button
                type="submit"
                class="btn bg-action hover:bg-action-hover border-none text-white"
                :disabled="!selectedDoctor || !selectedDay || !selectedTime"
            >Maak afspraak</button>
        </div>
    </form>
</div>

<script>
    function appointmentForm() {
        return {
            selectedDoctor: '',
            selectedDay: '',
            selectedTime: '',
            appointmentId: '',  // Dit model voor appointmentId, zorgt ervoor dat deze wordt doorgegeven in de hidden input
            availableDays: [],
            availableTimes: [],

            // Haal beschikbare dagen en tijden op voor een geselecteerde dokter
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
                        this.availableTimes = data.availableTimes || []; // Vul de tijden in
                        this.selectedDay = ''; // Reset de geselecteerde dag
                    })
                    .catch(error => console.error('Error fetching available data:', error));
            },

            // Methode om het appointmentId in te stellen bij het selecteren van een tijd
            setAppointmentId(event) {
                const selectedOption = event.target.selectedOptions[0];
                this.appointmentId = selectedOption.getAttribute('data-appointment-id'); // Zet de juiste appointmentId
            }
        }
    }
</script>
@endsection

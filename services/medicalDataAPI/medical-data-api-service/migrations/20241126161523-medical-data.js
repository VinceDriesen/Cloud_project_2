const { faker } = require("@faker-js/faker");

module.exports = {
  async up(db, client) {
    await db.createCollection("LabResult");
    await db.createCollection("VaccinationRecord");
    await db.createCollection("MedicalImage");
    await db.createCollection("Invoice");

    const labResults = generateLabResults(1000);
    await db.collection("LabResult").insertMany(labResults);

    const vaccinationRecords = generateVaccinationRecords(1000);
    await db.collection("VaccinationRecord").insertMany(vaccinationRecords);

    const medicalImages = generateMedicalImages(1000);
    await db.collection("MedicalImage").insertMany(medicalImages);

    const invoices = generateInvoices(1000);
    await db.collection("Invoice").insertMany(invoices);
  },

  async down(db, client) {
    await db.collection("LabResult").drop();
    await db.collection("VaccinationRecord").drop();
    await db.collection("MedicalImage").drop();
    await db.collection("Invoice").drop();
  },
};

function generateLabResults(count) {
  return Array.from({ length: count }, () => ({
    userId: faker.number.int({ min: 1, max: 10 }),
    testName: faker.lorem.words(2),
    resultValue: faker.number.float({ min: 0, max: 100 }).toFixed(2),
    unit: ["mg/dL", "mmol/L", "g/L"][Math.floor(Math.random() * 3)],
    referenceRange: "Normal",
    date: faker.date.recent().toISOString(),
    notes: faker.lorem.sentence(),
  }));
}

function generateVaccinationRecords(count) {
  return Array.from({ length: count }, () => ({
    userId: faker.number.int({ min: 1, max: 10 }),
    vaccineName: faker.helpers.arrayElement(["Pfizer", "Moderna", "AstraZeneca"]),
    dateAdministered: faker.date.past().toISOString(),
    administeredBy: faker.person.fullName(),
    notes: faker.lorem.sentence(),
  }));
}

function generateMedicalImages(count) {
  return Array.from({ length: count }, () => ({
    userId: faker.number.int({ min: 1, max: 10 }),
    imageUrl: faker.image.url(),
    description: faker.lorem.sentence(),
    dateUploaded: faker.date.recent().toISOString(),
  }));
}

function generateInvoices(count) {
  return Array.from({ length: count }, () => ({
    userId: faker.number.int({ min: 1, max: 10 }),
    amount: faker.number.float({ min: 50, max: 500 }).toFixed(2),
    dateIssued: faker.date.recent().toISOString(),
    status: faker.helpers.arrayElement(["PAID", "UNPAID", "PENDING"]),
  }));
}

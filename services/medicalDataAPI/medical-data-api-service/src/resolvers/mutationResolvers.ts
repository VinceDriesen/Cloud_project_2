import { ObjectId } from "mongodb";
import { connectDatabase } from "../database/database";
import { getLabResult } from "./queryResolvers";
import {LabResult, RequireFields, Resolvers, Invoice, MutationAddLabResultArgs, MutationUpdateLabResultArgs, MutationAddVaccinationRecordArgs, VaccinationRecord, MutationUploadMedicalImageArgs, MedicalImage, MutationAddInvoiceArgs} from "../types";
import { get } from "http";

export const addLabResult = async (parent: {}, args: RequireFields<MutationAddLabResultArgs, "userId" | "input">, context: any) => {
    const { userId, input } = args;

    if (!userId) {
        throw new Error("User ID is required to add lab result.");
    }

    if (!input) {
        throw new Error("Input is required to add lab result.");
    }

    try {
        const db = await connectDatabase();
        const result = await db.collection("LabResult").insertOne({
            userId,
            ...input
        });

        if (!result) {
            throw new Error("Error adding lab result.");
        }

        const addedResult: LabResult = {
            id: result.insertedId.toString(),
            userId: userId,
            testName: input.testName,
            resultValue: input.resultValue,
            unit: input.unit,
            referenceRange: input.referenceRange,
            date: input.date,
            notes: input.notes,
        };

        return addedResult;
    } catch (error) {
        console.error("Error adding lab result:", error);
        throw new Error("An error occurred while adding lab result.");
    }
};

export const updateLabResult = async (parent: {}, args: RequireFields<MutationUpdateLabResultArgs, "input" | "resultId">, context: any) => {
    const { resultId, input } = args;

    if (!resultId) {
        throw new Error("Result ID is required to update lab result.");
    }

    if (!input) {
        throw new Error("Input is required to update lab result.");
    }

    if (!ObjectId.isValid(resultId)) {
        throw new Error("Invalid resultId format.");
    }

    try {
        const db = await connectDatabase();

        const existingResult = await db.collection("LabResult").findOne({ _id: new ObjectId(resultId) });
        if (!existingResult) {
            throw new Error("Lab result not found with the given ID.");
        }

        const result = await db.collection("LabResult").updateOne(
            { _id: new ObjectId(resultId) },
            { $set: input }
        );

        if (result.modifiedCount === 0) {
            console.log("No modifications were made.");
            throw new Error("No changes were made to the lab result.");
        }

        return getLabResult({}, { resultId }, {});
    } catch (error) {
        console.error("Error updating lab result:", error);
        throw new Error("An error occurred while updating lab result.");
    }
};


export const addVaccinationRecord = async (parent: {}, args: RequireFields<MutationAddVaccinationRecordArgs, "userId" | "input">, context: any) => {
    const { userId, input } = args;

    if (!userId) {
        throw new Error("User ID is required to add vaccination record.");
    }

    if (!input) {
        throw new Error("Input is required to add vaccination record.");
    }

    try {
        const db = await connectDatabase();
        const result = await db.collection("VaccinationRecord").insertOne({
            userId,
            ...input
        });

        if (!result) {
            throw new Error("Error adding vaccination record.");
        }

        const addedRecord: VaccinationRecord = {
            id: result.insertedId.toString(),
            userId: userId,
            vaccineName: input.vaccineName,
            dateAdministered: input.dateAdministered,
            administeredBy: input.administeredBy,
            notes: input.notes,
        };
        return addedRecord;

    } catch (error) {
        console.error("Error adding vaccination record:", error);
        throw new Error("An error occurred while adding vaccination record.");
    }
};

export const uploadMedicalImage = async (parent: {}, args: RequireFields<MutationUploadMedicalImageArgs, "userId" | "input">, context: any) => {
    const { userId, input } = args;

    if (!userId) {
        throw new Error("User ID is required to upload medical image.");
    }

    if (!input) {
        throw new Error("Input is required to upload medical image.");
    }

    try {
        const db = await connectDatabase();
        const result = await db.collection("MedicalImage").insertOne({
            userId,
            ...input
        });

        if (!result) {
            throw new Error("Error uploading medical image.");
        }

        const addedImage : MedicalImage = {
            id: result.insertedId.toString(),
            userId: userId,
            imageUrl: input.imageUrl,
            dateUploaded: input.dateUploaded,
            description: input.description,
        };

        return addedImage;
    } catch (error) {
        console.error("Error uploading medical image:", error);
        throw new Error("An error occurred while uploading medical image.");
    }
};


export const addInvoice = async (parent: {}, args: RequireFields<MutationAddInvoiceArgs, "userId" | "input">, context: any) => {
    const { userId, input } = args;

    if (!userId) {
        throw new Error("User ID is required to add invoice.");
    }

    if (!input) {
        throw new Error("Input is required to add invoice.");
    }

    try {
        const db = await connectDatabase();
        const result = await db.collection("Invoice").insertOne({
            userId,
            ...input
        });

        if (!result) {
            throw new Error("Error adding invoice.");
        }

        const addedInvoice: Invoice = {
            id: result.insertedId.toString(),
            userId: userId,
            dateIssued: input.dateIssued,
            amount: input.amount,
            status: input.status,
        };

        return addedInvoice;
    } catch (error) {
        console.error("Error adding invoice:", error);
        throw new Error("An error occurred while adding invoice.");
    }
};
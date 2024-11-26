import { connectDatabase } from "../database/database";
import { ObjectId } from "mongodb";
import { LabResult, RequireFields, Resolvers, QueryGetLabResultsArgs, QueryGetLabResultArgs, Invoice, MedicalImage, QueryGetInvoicesArgs, VaccinationRecord } from "../types";

export const getLabResults = async (
    parent: {},
    args: RequireFields<QueryGetLabResultsArgs, "userId">,
    context: any
) => {
    const { userId, filter } = args;

    if (!userId) {
        throw new Error("User ID is required to fetch lab results.");
    }

    try {
        const db = await connectDatabase();

        const query: any = { userId: userId };

        if (filter?.testName) {
            query.testName = filter.testName;
        }

        if (filter?.dateRange) {
            const { startDate, endDate } = filter.dateRange;
            query.date = {};
            if (startDate) {
                query.date.$gte = new Date(startDate).toISOString();
            }
            if (endDate) {
                query.date.$lte = new Date(endDate).toISOString();
            }
        }

        const results = await db
            .collection("LabResult")
            .find(query)
            .toArray();
        if (!results || results.length === 0) {
            throw new Error("No lab results found for the given user ID and filter.");
        }

        const mappedResults: LabResult[] = results.map(result => ({
            id: result._id.toString(),
            userId: result.userId,
            testName: result.testName,
            resultValue: result.resultValue,
            unit: result.unit,
            referenceRange: result.referenceRange,
            date: result.date,
            notes: result.notes,
        }));

        return mappedResults;
    } catch (error) {
        console.error("Error fetching lab results:", error);
        throw new Error("An error occurred while fetching lab results.");
    }
};


export const getLabResult = async (parent: {}, args: RequireFields<QueryGetLabResultArgs, "resultId">, context: any) => {
    const { resultId } = args;

    if (!resultId) {
        throw new Error("User ID is required to fetch lab result.");
    }

    try {
        const db = await connectDatabase();
        const result = await db
            .collection("LabResult")
            .findOne({ _id: new ObjectId(resultId) });

        if (!result) {
            throw new Error("No lab result found for the given user ID.");
        }

        const mappedResult: LabResult = {
            id: result._id.toString(),
            userId: result.userId,
            testName: result.testName,
            resultValue: result.resultValue,
            unit: result.unit,
            referenceRange: result.referenceRange,
            date: result.date,
            notes: result.notes,
        };

        return mappedResult;
    } catch (error) {
        console.error("Error fetching lab result:", error);
        throw new Error("An error occurred while fetching lab result.");
    }
};

export const getInvoices = async (parent: {}, args: RequireFields<QueryGetInvoicesArgs, "userId">, context: any) => {
    const { userId } = args;

    if (!userId) {
        throw new Error("User ID is required to fetch lab results.");
    }
    try {
        const db = await connectDatabase();
        const invoices = await db
            .collection("Invoice")
            .find({ userId: userId }) 
            .toArray();

        if (!invoices) {
            throw new Error("No invoices found.");
        }

        const mappedInvoices :Invoice[] = invoices.map(invoice => ({
            id: invoice._id.toString(),
            userId: invoice.userId,
            amount: invoice.amount,
            dateIssued: invoice.dateIssued,
            status: invoice.status,
        }));

        return mappedInvoices;
    } catch (error) {
        console.error("Error fetching invoices:", error);
        throw new Error("An error occurred while fetching invoices.");
    }
};

export const getMedicalImages = async (parent: {}, args: RequireFields<QueryGetInvoicesArgs, "userId">, context: any) => {
    const { userId } = args;

    if (!userId) {
        throw new Error("User ID is required to fetch medical images.");
    }
    try {
        const db = await connectDatabase();
        const images = await db
            .collection("MedicalImage")
            .find({ userId: userId }) 
            .toArray();
        console.log(images);
        if (!images) {
            throw new Error("No medical images found.");
        }

        const mappedImages : MedicalImage[] = images.map(image => ({
            id: image._id.toString(),
            userId: image.userId,
            imageUrl: image.imageUrl,
            dateUploaded: image.dateUploaded,
            description: image.description,
        }));

        return mappedImages;
    } catch (error) {
        console.error("Error fetching medical images:", error);
        throw new Error("An error occurred while fetching medical images.");
    }
};

export const getVaccinationRecords = async (parent: {}, args: RequireFields<QueryGetInvoicesArgs, "userId">, context: any) => {
    const { userId } = args;

    if (!userId) {
        throw new Error("User ID is required to fetch vaccination records.");
    }
    try {
        const db = await connectDatabase();
        const records = await db
            .collection("VaccinationRecord")
            .find({ userId: userId }) 
            .toArray();

        if (!records) {
            throw new Error("No vaccination records found.");
        }

        const mappedRecords : VaccinationRecord[] = records.map(record => ({
            id: record._id.toString(),
            userId: record.userId,
            vaccineName: record.vaccineName,
            dateAdministered: record.dateAdministered,
            administeredBy: record.administeredBy,
            notes: record.notes,
        }));

        return mappedRecords;
    } catch (error) {
        console.error("Error fetching vaccination records:", error);
        throw new Error("An error occurred while fetching vaccination records.");
    }
}
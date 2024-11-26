import {Resolvers} from "../types";
import { getLabResults, getLabResult, getInvoices, getMedicalImages, getVaccinationRecords } from "./queryResolvers";
import { addLabResult, updateLabResult, addVaccinationRecord, uploadMedicalImage, addInvoice } from "./mutationResolvers";

export const resolvers: Resolvers = {
    Query: {
        getLabResults: async (parent, args, context) => {
            return getLabResults(parent, args, context);
        },
        getLabResult: async (parent, args, context) => {
            return getLabResult(parent, args, context);
        },
        getInvoices: async (parent, args, context) => {
            return getInvoices(parent, args, context);
        },
        getMedicalImages: async (parent, args, context) => {
            return getMedicalImages(parent, args, context);
        },
        getVaccinationRecords: async (parent, args, context) => {
            return getVaccinationRecords(parent, args, context);
        }
    },
    Mutation: {
        addLabResult: async (parent, args, context) => {
            return addLabResult(parent, args, context);
        },
        updateLabResult: async (parent, args, context) => {
            return updateLabResult(parent, args, context);
        },
        addVaccinationRecord: async (parent, args, context) => {
            return addVaccinationRecord(parent, args, context);
        },
        uploadMedicalImage: async (parent, args, context) => {
            return uploadMedicalImage(parent, args, context);
        },
        addInvoice: async (parent, args, context) => {
            return addInvoice(parent, args, context);
        }
    },
};

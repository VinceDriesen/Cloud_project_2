import { MongoClient, Db } from 'mongodb';
import dotenv from 'dotenv';

dotenv.config();

const MONGO_URI = `mongodb://${process.env.MONGO_USER}:${process.env.MONGO_PASS}@${process.env.MONGO_HOST}:${process.env.MONGO_PORT}`;

let client: MongoClient;
let database: Db;

export async function connectDatabase(): Promise<Db> {
    if (database) return database;
    try {
        client = new MongoClient(MONGO_URI);
        await client.connect();
        console.log("MongoDB connected successfully");

        database = client.db(process.env.MONGO_DB as string);
        return database;
    } catch (error) {
        console.error("Error connecting to MongoDB: ", error);
        throw new Error("Error connecting to MongoDB");
    }
}

export async function disconnectDatabase(): Promise<void> {
    if (client) {
        await client.close();
        console.log("MongoDB disconnected successfully");
    }
}

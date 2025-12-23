import { AIProjectClient } from "@azure/ai-projects";
import { DefaultAzureCredential } from "@azure/identity";
import * as dotenv from "dotenv";

dotenv.config();

const endpoint = process.env.AZURE_AI_PROJECT_ENDPOINT;
const azPath = "C:\\Program Files (x86)\\Microsoft SDKs\\Azure\\CLI2\\wbin";

if (!process.env.PATH.includes(azPath)) {
    process.env.PATH = `${azPath};${process.env.PATH}`;
}

async function debug() {
    const client = new AIProjectClient(endpoint, new DefaultAzureCredential());
    const agentsClient = client.agents;

    console.log("== Agents Client Methods ==");
    console.log(Object.getOwnPropertyNames(Object.getPrototypeOf(agentsClient)));
}

debug();

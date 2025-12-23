import { AIProjectClient } from "@azure/ai-projects";
import { DefaultAzureCredential } from "@azure/identity";
import * as dotenv from "dotenv";

dotenv.config();

const endpoint = process.env.AZURE_AI_PROJECT_ENDPOINT;
const azPath = "C:\\Program Files (x86)\\Microsoft SDKs\\Azure\\CLI2\\wbin";

if (!process.env.PATH.includes(azPath)) {
    process.env.PATH = `${azPath};${process.env.PATH}`;
}

async function listDeployments() {
    console.log("Fetching Model Deployments...");
    try {
        const client = new AIProjectClient(endpoint, new DefaultAzureCredential());
        const deployments = await client.deployments.list();
        
        console.log("== Active Deployments ==");
        for await (const d of deployments) {
            console.log(`- Name: ${d.name}, Model: ${d.modelName || 'N/A'}`);
        }
    } catch (err) {
        console.error("Error listing deployments:", err.message);
    }
}

listDeployments();

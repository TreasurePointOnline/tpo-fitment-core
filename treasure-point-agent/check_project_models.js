import { AIProjectClient } from "@azure/ai-projects";
import { DefaultAzureCredential } from "@azure/identity";
import * as dotenv from "dotenv";

dotenv.config();

const endpoint = process.env.AZURE_AI_PROJECT_ENDPOINT;
const azPath = "C:\\Program Files (x86)\\Microsoft SDKs\\Azure\\CLI2\\wbin";

if (!process.env.PATH.includes(azPath)) {
    process.env.PATH = `${azPath};${process.env.PATH}`;
}

async function listProjectConnections() {
    console.log("🔍 Checking what models your project can actually see...");
    try {
        const client = new AIProjectClient(endpoint, new DefaultAzureCredential());
        
        // In the new SDK, we check 'connections' to see linked AI resources
        const connections = await client.connections.list();
        
        console.log("\n== Linked Connections ==");
        for await (const conn of connections) {
            console.log(`- Name: ${conn.name} (Type: ${conn.connectionType})`);
        }

        // Also check inference deployments if possible
        try {
            const deployments = await client.deployments.list();
            console.log("\n== Project Deployments (Model Names) ==");
            for await (const d of deployments) {
                console.log(`- Model Name to use: "${d.name}" (Actual Model: ${d.modelName})`);
            }
        } catch (e) {
            console.log("\n(Could not list deployments directly, likely permission or API version issue)");
        }

    } catch (err) {
        console.error("Error:", err.message);
    }
}

listProjectConnections();

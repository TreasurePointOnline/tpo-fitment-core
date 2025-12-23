import { AIProjectClient } from "@azure/ai-projects";
import { DefaultAzureCredential } from "@azure/identity";
import * as dotenv from "dotenv";

dotenv.config();

const endpoint = process.env.AZURE_AI_PROJECT_ENDPOINT;
const agentId = "asst_aem40lnjMzj0Gjrfg20862bN"; 
const azPath = "C:\\Program Files (x86)\\Microsoft SDKs\\Azure\\CLI2\\wbin";

if (!process.env.PATH.includes(azPath)) {
    process.env.PATH = `${azPath};${process.env.PATH}`;
}

async function checkAgent() {
    console.log(`Checking Agent ${agentId}...`);
    try {
        const client = new AIProjectClient(endpoint, new DefaultAzureCredential());
        const agent = await client.agents.getAgent(agentId);
        
        console.log("\n== Agent Current Config ==");
        console.log(`- Model Name assigned in Azure: "${agent.model}"`);
        console.log("- Tools enabled:", agent.tools?.map(t => t.type).join(", "));
        
    } catch (err) {
        console.error("Error:", err.message);
    }
}

checkAgent();

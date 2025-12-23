import { AIProjectClient } from "@azure/ai-projects";
import { DefaultAzureCredential } from "@azure/identity";
import * as dotenv from "dotenv";

dotenv.config();

const connectionString = process.env.PROJECT_CONNECTION_STRING;

if (!connectionString) {
  throw new Error("Please set the PROJECT_CONNECTION_STRING environment variable.");
}

async function main() {
  console.log("== Starting Treasure Point Agent Verification ==");
  
  try {
    // Ensure Azure CLI is in PATH for DefaultAzureCredential
    const azPath = "C:\\Program Files (x86)\\Microsoft SDKs\\Azure\\CLI2\\wbin";
    if (!process.env.PATH.includes(azPath)) {
        process.env.PATH = `${azPath};${process.env.PATH}`;
    }

    const endpoint = process.env.AZURE_AI_PROJECT_ENDPOINT;
    console.log(`Connecting to: ${endpoint}`);

    const client = new AIProjectClient(
      endpoint,
      new DefaultAzureCredential()
    );

    // List available agents to verify connection
    console.log("Authenticated! Listing current agents...");
    const agentsIterator = await client.agents.listAgents();
    
    let agentCount = 0;
    let firstAgent = null;

    for await (const agent of agentsIterator) {
        agentCount++;
        if (!firstAgent) firstAgent = agent;
        console.log(`Found Agent: ${agent.name} (${agent.id})`);
    }

    if (agentCount === 0) {
        console.log("No agents found. Creating a new 'Treasure Point Specialist'...");
        const agent = await client.agents.createAgent("gpt-4o", {
            name: "treasure-point-specialist",
            instructions: "You are a helpful assistant for Treasure Point Online."
        });
        console.log(`Created Agent: ${agent.id}`);
    } else {
        console.log(`Found ${agentCount} existing agent(s).`);
        console.log(`Using Agent ID: ${firstAgent.id}`);
    }

    console.log("== Connection Successful ==");

  } catch (err) {
    console.error("Error connecting to Azure AI:", err);
  }
}

main();

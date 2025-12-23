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

async function updateAgent() {
    console.log("Upgrading Agent with File System and Web tools...");
    const client = new AIProjectClient(endpoint, new DefaultAzureCredential());

    const tools = [
        {
            type: "function",
            function: {
                name: "fetch_web_page",
                description: "Fetches HTML of a URL for SEO and content analysis.",
                parameters: { type: "object", properties: { url: { type: "string" } }, required: ["url"] }
            }
        },
        {
            type: "function",
            function: {
                name: "list_files",
                description: "Lists files in a project directory.",
                parameters: { type: "object", properties: { directory: { type: "string" } } }
            }
        },
        {
            type: "function",
            function: {
                name: "read_file",
                description: "Reads the content of a file in the project.",
                parameters: { type: "object", properties: { filePath: { type: "string" } }, required: ["filePath"] }
            }
        },
        {
            type: "function",
            function: {
                name: "write_file",
                description: "Writes content to a file in the project. Use this to help the user design code or update text.",
                parameters: { 
                    type: "object", 
                    properties: { 
                        filePath: { type: "string" },
                        content: { type: "string" }
                    }, 
                    required: ["filePath", "content"] 
                }
            }
        }
    ];

    await client.agents.updateAgent(agentId, {
        model: "gpt-4o-mini",
        instructions: "You are the Treasure Point Specialist. You help manage the car audio website. You can read/write local project files and check the live site. Always verify changes by checking the local files or live site. You remember the history of this project.",
        tools: tools
    });

    console.log("✅ Upgrade Complete! Agent is now a full-stack assistant.");
}

updateAgent();
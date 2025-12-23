import { AIProjectClient } from "@azure/ai-projects";
import { DefaultAzureCredential } from "@azure/identity";
import * as dotenv from "dotenv";
import * as readline from "readline";
import * as fs from "fs";
import * as path from "path";

dotenv.config();

// --- CONFIGURATION ---
const endpoint = process.env.AZURE_AI_PROJECT_ENDPOINT;
const agentId = "asst_aem40lnjMzj0Gjrfg20862bN"; 
const azPath = "C:\\Program Files (x86)\\Microsoft SDKs\\Azure\\CLI2\\wbin";
const THREAD_FILE = ".thread_id";

// Ensure Path for Auth
if (!process.env.PATH.includes(azPath)) {
    process.env.PATH = `${azPath};${process.env.PATH}`;
}

// --- TOOLS (The Agent's Capabilities) ---
const tools = {
    "fetch_web_page": async ({ url }) => {
        console.log(`\n[Tool] 👀 Agent is looking at: ${url}...`);
        try {
            const response = await fetch(url);
            const text = await response.text();
            return text.substring(0, 10000); 
        } catch (error) {
            return `Error fetching page: ${error.message}`;
        }
    },
    "list_files": async ({ directory = "." }) => {
        console.log(`\n[Tool] 📂 Agent is listing files in: ${directory}...`);
        try {
            const files = fs.readdirSync(directory);
            return files.join(", ");
        } catch (error) {
            return `Error listing files: ${error.message}`;
        }
    },
    "read_file": async ({ filePath }) => {
        console.log(`\n[Tool] 📖 Agent is reading: ${filePath}...`);
        try {
            const content = fs.readFileSync(filePath, "utf-8");
            return content;
        } catch (error) {
            return `Error reading file: ${error.message}`;
        }
    },
    "write_file": async ({ filePath, content }) => {
        console.log(`\n[Tool] ✍️ Agent is writing to: ${filePath}...`);
        try {
            fs.writeFileSync(filePath, content, "utf-8");
            return `Successfully wrote to ${filePath}`;
        } catch (error) {
            return `Error writing file: ${error.message}`;
        }
    }
};

async function main() {
    console.log("== 💎 Treasure Point AI Terminal 💎 ==");
    
    const client = new AIProjectClient(endpoint, new DefaultAzureCredential());
    
    // --- THREAD PERSISTENCE (Memory) ---
    let threadId;
    if (fs.existsSync(THREAD_FILE)) {
        threadId = fs.readFileSync(THREAD_FILE, "utf-8");
        console.log(`Connecting to existing brain (Thread: ${threadId})...`);
    } else {
        console.log("Connecting to a new brain...");
        const thread = await client.agents.threads.create();
        threadId = thread.id;
        fs.writeFileSync(THREAD_FILE, threadId);
    }

    console.log("Ready!");
    console.log("Type your message and press Enter. (Type 'exit' to quit)\n");

    const rl = readline.createInterface({
        input: process.stdin,
        output: process.stdout
    });

    const askQuestion = () => {
        rl.question("You: ", async (userInput) => {
            if (userInput.toLowerCase() === 'exit') {
                rl.close();
                return;
            }

            try {
                await client.agents.messages.create(threadId, "user", userInput);
                process.stdout.write("Agent: (Thinking) ");
                
                let run = await client.agents.runs.createAndPoll(threadId, agentId);
                
                while (run.status === "requires_action") {
                    process.stdout.write("\n[Action] Agent using tool...\n");
                    const toolCalls = run.required_action?.submit_tool_outputs.tool_calls || [];
                    const toolOutputs = [];

                    for (const toolCall of toolCalls) {
                        const functionName = toolCall.function.name;
                        const args = JSON.parse(toolCall.function.arguments);
                        if (tools[functionName]) {
                            const output = await tools[functionName](args);
                            toolOutputs.push({ tool_call_id: toolCall.id, output: JSON.stringify(output) });
                        }
                    }

                    if (toolOutputs.length > 0) {
                        run = await client.agents.runs.submitToolOutputs(threadId, run.id, toolOutputs).poll();
                    } else { break; }
                }

                process.stdout.write("\n");

                if (run.status === "completed") {
                    const messages = await client.agents.messages.list(threadId);
                    const lastMessage = messages.data[0]; 
                    if (lastMessage.role === "assistant") {
                        const textContent = lastMessage.content.find(c => c.type === "text");
                        if (textContent) {
                            console.log(`Agent: ${textContent.text.value}\n`);
                        }
                    }
                } else {
                    console.log(`[Status] Run ended with status: ${run.status}\n`);
                }
            } catch (err) {
                console.error("\n❌ Error:", err.message);
            }
            askQuestion();
        });
    };

    askQuestion();
}

main();
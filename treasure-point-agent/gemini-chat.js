import { GoogleGenerativeAI } from "@google/generative-ai";
import * as dotenv from "dotenv";
import * as readline from "readline";
import * as fs from "fs";

dotenv.config();

// --- CONFIGURATION ---
const genAI = new GoogleGenerativeAI(process.env.GOOGLE_API_KEY);
const model = genAI.getGenerativeModel({
    model: "gemini-1.5-flash", // Using your preferred model!
    systemInstruction: "You are the Treasure Point Specialist. You help manage the car audio website. You have access to local project files and can check the live site. You are efficient and focused on SEO and clean code."
});

const MEMORY_FILE = "shared_brain.json";

// --- TOOLS ---
const functions = {
    fetch_web_page: async (url) => {
        console.log(`\n[Gemini Tool] 👀 Looking at: ${url}...`);
        try {
            const response = await fetch(url);
            const text = await response.text();
            return text.substring(0, 10000);
        } catch (e) { return `Error: ${e.message}`; }
    },
    list_files: async (dir = ".") => {
        console.log(`\n[Gemini Tool] 📂 Listing files...`);
        return fs.readdirSync(dir).join(", ");
    },
    read_file: async (filePath) => {
        console.log(`\n[Gemini Tool] 📖 Reading: ${filePath}...`);
        return fs.readFileSync(filePath, "utf-8");
    }
};

async function main() {
    console.log("== ♊ Treasure Point GEMINI Terminal (1.5 Flash) ==");
    
    // Load local memory
    let history = [];
    if (fs.existsSync(MEMORY_FILE)) {
        history = JSON.parse(fs.readFileSync(MEMORY_FILE, "utf-8"));
        console.log("Memory loaded from Shared Brain.");
    }

    const chat = model.startChat({ history });

    const rl = readline.createInterface({ input: process.stdin, output: process.stdout });

    const ask = () => {
        rl.question("You: ", async (input) => {
            if (input.toLowerCase() === 'exit') {
                // Save brain before exit
                fs.writeFileSync(MEMORY_FILE, JSON.stringify(await chat.getHistory(), null, 2));
                rl.close();
                return;
            }

            try {
                process.stdout.write("Gemini: (Thinking) ");
                const result = await chat.sendMessage(input);
                process.stdout.write("\n");
                console.log(`Gemini: ${result.response.text()}\n`);
                
                // Keep memory updated
                fs.writeFileSync(MEMORY_FILE, JSON.stringify(await chat.getHistory(), null, 2));
            } catch (err) {
                console.error("\n❌ Error:", err.message);
            }
            ask();
        });
    };

    console.log("Ready! (Type 'exit' to save brain and quit)\n");
    ask();
}

main();

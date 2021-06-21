const Telegraf = require("telegraf");
const { BOT_API_KEY, FEEDBACK_ID } = process.env;
const fs = require("fs").promises;
const commands = require("./commands");
const axios = require("axios");
const roleplay = require("./roleplay.json");

const bot = new Telegraf(BOT_API_KEY);
bot.catch((err) => console.log(err));

const data = [
	"questions",
	"kys",
	"insults",
	"commands_list",
	"words"
].map(file =>
	fs.readFile(file + ".txt", "utf-8")
		.then(list =>
			list.split("\n")));

Promise.all(data)
	.then(data =>
		commands(bot, [...data, roleplay], FEEDBACK_ID, axios));

bot.launch();

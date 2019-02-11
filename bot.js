const Telegraf = require("telegraf");
const { BOT_API_KEY } = process.env;
const fs = require("fs").promises;
const commands = require("./commands");

const bot = new Telegraf(BOT_API_KEY);

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
		commands(bot, data));

bot.launch();

const random = require("./random");
const insults_fun = require("./insult");
const words_fun = require("./words");
const is = require("./is");
module.exports = (bot, [ questions, kys, insults, commands_list, words ]) => {

	bot.command("question", (ctx) => ctx.reply(random(questions)()));
	bot.command("word", (ctx) => ctx.reply(random(words)()));
	bot.command("words", (ctx) => ctx.reply(words_fun(random, words)(ctx)));
	bot.telegram.getMe()
		.then(bot_user => {

			const default_text = (command, text) => `Do you want to ${text} `
				+ `yourself?\nIf no, reply to someone with /${command} to kill`
				+ ` them or run /${command} username/name.\nYou can suggest `
				+ `more /${command} replies using /feedback`;

			bot.command("insult", (ctx) => ctx.reply(insults_fun(random,
				insults, default_text("insult", "insult"), "Watch who you talk"
				+ " to.", ["@" + bot_user.username, bot_user.firstName])(ctx)));

			bot.command("kys", (ctx) => ctx.reply(insults_fun(random, kys,
				default_text("kys", "kill"), "I can't be killed.",
				["@" + bot_user.username, bot_user.firstName])(ctx)));

		});

	bot.command("commands", (ctx) => ctx.reply(commands_list.join("\n")));
	bot.command("is", (ctx) => ctx.reply(is(random)(ctx)));
	bot.command("are", (ctx) => ctx.reply(is(random)(ctx)));
	bot.command("can", (ctx) => ctx.reply(is(random)(ctx)));
	bot.command("will", (ctx) => ctx.reply(is(random)(ctx)));
	bot.command("shall", (ctx) => ctx.reply(is(random)(ctx)));
	bot.command("was", (ctx) => ctx.reply(is(random)(ctx)));
	bot.command("do", (ctx) => ctx.reply(is(random)(ctx)));
	bot.command("does", (ctx) => ctx.reply(is(random)(ctx)));
	bot.command("did", (ctx) => ctx.reply(is(random)(ctx)));
	bot.command("should", (ctx) => ctx.reply(is(random)(ctx)));
	bot.command("coin", (ctx) => ctx.reply(random(["Heads", "Tails"])()));
	bot.command("help", (ctx) => ctx.reply("You can either check /commands "
		+ "for a short overview or check the [Help Page]"
		+ "(https://t.me/quadnite/9).", {parse_mode: "Markdown"}));
	bot.command("rate", (ctx) => ctx.reply("[Vote for me on Telegram "
		+ "Directory!](https://t.me/tgdrbot?start=quadnite_bot)", {parse_mode:
			"Markdown"}));

};

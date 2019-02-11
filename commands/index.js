const random = require("./random");
const insults_fun = require("./insult");
const is = require("./is");
module.exports = (bot, [ questions, kys, insults, commands_list ]) => {

	bot.command("question", (ctx) => ctx.reply(random(questions)()));

	const default_text = (command, text) => `Do you want to ${text} yourself?\n`
		+ `If no, reply to someone with /${command} to kill them or run /${command} username/name.\n`
		+ `You can suggest more /${command} replies using /feedback`;
	bot.command("insult", (ctx) => ctx.reply(insults_fun(random, insults, default_text("insult", "insult"))(ctx)));
	bot.command("kys", (ctx) => ctx.reply(insults_fun(random, kys, default_text("kys", "kill"))(ctx)));

	bot.command("commands", (ctx) => ctx.reply(commands_list.join("\n")));
	bot.command("is", (ctx) => ctx.reply(is(random)));
	bot.command("are", (ctx) => ctx.reply(is(random)));
	bot.command("can", (ctx) => ctx.reply(is(random)));
	bot.command("will", (ctx) => ctx.reply(is(random)));
	bot.command("shall", (ctx) => ctx.reply(is(random)));
	bot.command("was", (ctx) => ctx.reply(is(random)));
	bot.command("do", (ctx) => ctx.reply(is(random)));
	bot.command("does", (ctx) => ctx.reply(is(random)));
	bot.command("did", (ctx) => ctx.reply(is(random)));
	bot.command("should", (ctx) => ctx.reply(is(random)));
	bot.command("coin", (ctx) => ctx.reply(random(["Heads", "Tails"])()));

};

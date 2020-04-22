function expand(words, text) {

	const letters = text.trim().toLowerCase().split("");
	return letters.map((letter) => {

		const wordsWithLetter = words.filter(i => i.match(RegExp(`^${letter}`, "i")));
		const word = wordsWithLetter[Math.floor(Math.random() * wordsWithLetter.length)];
		return word;

	}).reduce((acc, cur) => acc + " " + cur);

}


module.exports = (words = []) => (ctx) => {

	const message = ctx.message.text.replace(/^[^ ]+/, "");
	if (message) {

		return expand(words, message);

	} else {

		if (ctx.message.reply_to_message)
			return expand(words, ctx.message.reply_to_message.text);
		else
			return "Need text to expand. Send /expand text or reply to a "
			+ "message with /expand";

	}

};

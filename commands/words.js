module.exports = (random, words) => (ctx) => {

	const text_array = ctx.message.text.split(" ");
	if (text_array.length == 1) {

		return random(words)(10);

	} else {

		const input = parseInt(text_array[1]);
		if (input && input > 0)
			if (input < 50)
				return random(words)(input);
			else
				return "Too many words.";
		else
			return "Not a valid number.";

	}

};

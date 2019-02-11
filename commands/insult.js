module.exports = (random, kys, default_text) => (ctx) => {

	if (ctx.message.reply_to_message) {

		const { from } = ctx.message.reply_to_message;
		const name = from.username ? "@" + from.username : from.firstName;
		return random(kys)().replace(/##name##/g, name);

	} else {

		const text_array = ctx.message.text.split(" ");
		if (text_array.length > 1) {

			const name = text_array[1];
			return random(kys)().replace(/##name##/g, name);

		} else
			return default_text;

	}

};

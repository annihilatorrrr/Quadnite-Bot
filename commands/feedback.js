module.exports = (feedback_id) => (ctx) => {

	const message = ctx.message.text.replace(/^[^ ]+/, "");
	if (message) {

		ctx.forwardMessage(feedback_id);
		return "Thanks for the feedback";

	} else {

		return "To send feedback type in /feedback followed by the feedback";

	}

};

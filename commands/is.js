module.exports = (random) => (ctx) =>
{

	const text_array = ctx.message.text.split(" ");
	if (text_array.length == 1)
		return "You know, you also have to ask the question.";

	return random([["Yes", "Yep", "Yeah", "Yus", "Ja", "Ya", "Aye", "Ay", "Oui"],
		["No", "Nopes", "Nu", "Nah", "Nein", "Naw", "Nay", "Yesn't"]][Math.round(
		Math.random())])();

};

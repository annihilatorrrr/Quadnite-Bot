const ffmpeg = require("fluent-ffmpeg");
const fs = require("fs");
const FormData = require("form-data");

function ugokiUpload(axios, ugokiRoot, ctx, category, path) {

	const form = new FormData();
	form.append("file", fs.createReadStream(path));
	return axios.post(`new_suggestion/${category}`, form,
		{ headers: form.getHeaders(), baseURL: ugokiRoot })
		.then(() => {

			ctx.reply("Suggestion added.");
			fs.unlink(path, () => {});

		})
		.catch((err) => {

			if (err.response.status == 404)
				ctx.reply("Category doesn't exist");
			else if (err.response.status == 409)
				ctx.reply("Already suggested / added.");
			else
				ctx.reply("No clue what the hell happened but adding suggestion failed.");

		});

}

module.exports = (axios, apiToken, ugokiRoot) => (ctx) => {

	const category = ctx.message.text.split(" ")[1];
	const reply = ctx.message.reply_to_message;

	if (category && reply && reply.animation) {

		return ctx.telegram.getFile(reply.animation.file_id)
			.then(resp => {

				return axios({
					method: "get",
					url: `https://api.telegram.org/file/bot${apiToken}/${resp.file_path}`,
					responseType: "stream"
				})
					.then(async (response) => {

						let stream = response.data;
						let path = `data/${resp.file_unique_id}`;
						const writer = fs.createWriteStream(path);
						if (!resp.file_path.match(/\.gif$/)) {

							await stream.pipe(writer);
							stream = ffmpeg(path)
								.on("error", function () {

									fs.unlink(path, () => {});
									ctx.reply("Something went wrong processing the gif");


								})
								.on("end", function () {

									fs.unlink(path, () => {});
									ugokiUpload(axios, ugokiRoot, ctx, category, path + ".gif");

								})
								.output(path + ".gif")
								.outputFormat("gif")
								.run();

						} else
							ugokiUpload(axios, ugokiRoot, category, ctx, path);

					}
					);

			});

	} else
		ctx.reply("Reply to a gif with /suggest [category] "
			+ "to suggest it to be used for [category]");

};

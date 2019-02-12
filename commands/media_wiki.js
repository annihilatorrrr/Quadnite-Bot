module.exports = (axios, url) => (ctx) => {

	const message = ctx.message.text.replace(/^[^ ]+/, "");
	if (message) {

		return axios.get(`${url}?action=opensearch&format=json&search=`
			+ `${encodeURIComponent(message)}`)
			.then((res) => {

				const names = res.data[1];
				const urls = res.data[3];
				if (names.length == 0)
					return "No results found";
				return "Results\n\n" + names.map((val, index) =>
					`<a href="${urls[index]}">${val}</a>`).join("\n");

			});

	} else
		return Promise.resolve("Missing search query.");

};

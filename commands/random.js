module.exports = (list = []) => (n = 1) =>
	Array(n)
		.fill(0)
		.map(() => list[Math.floor(Math.random() * list.length)])
		.join("\n");

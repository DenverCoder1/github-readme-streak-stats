export default function handler(req, res) {
  res.setHeader("Content-Type", "image/png");
  const png = Buffer.from(
  "iVBORw0KGgoAAAANSUhEUgAAAAoAAAAKCAYAAACNMs+9AAAAF0lEQVR42mP8z8AARMAgFoYGhgYGBgAAtxkJtZLxuxAAAAAASUVORK5CYII=",
  "base64"
  );
  res.status(200).send(png);
}

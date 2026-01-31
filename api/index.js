export default function handler(req, res) {
  res.setHeader("Content-Type", "image/png");
  const png = Buffer.from(
    "iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMB/6XKZQAAAABJRU5ErkJggg==",
    "base64"
  );
  res.status(200).send(png);
}

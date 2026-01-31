export default function handler(req, res) {
  res.status(200).json(
    { message: "Hello, world from VERCEL API!",
      timestamp: new Date().toISOString(),
     });
}
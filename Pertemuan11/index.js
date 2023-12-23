import express from "express";
import apiRouting from "./routes/api.js";

const app = express();
const port = 3000;

app.use(express.json());
app.use(express.urlencoded({ extended: true }));

app.use(apiRouting);

app.listen(port, () => {
  console.log(`Server running on http://localhost:${port}`);
});

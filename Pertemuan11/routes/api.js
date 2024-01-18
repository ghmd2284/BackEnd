import express from "express";
import StudentController from "../controller/StudentController.js";
import AuthController from "../controller/AuthController.js";
import auth from "../middleware/auth.js";

const router = express.Router();

router.route("/students")
  .get(auth, StudentController.index)
  .post(auth, StudentController.store);

router.route("/students/:id")
  .put(auth, StudentController.update)
  .delete(auth, StudentController.destroy)
  .get(auth, StudentController.show);

router.post("/login", AuthController.login);
router.post("/register", AuthController.register);

export default router;

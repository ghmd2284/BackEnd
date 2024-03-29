import Student from "../models/students.js";
import { Sequelize } from "sequelize";
const REQUIRED_DATA = ["nama", "nim", "email", "jurusan"];

export class StudentController {
  index = async (req, res) => {
    try {
      const { name, major, sort, order } = req.query;
      const query = {};

      if (name) query.nama = { [Sequelize.Op.like]: `%${name}%` };
      if (major) query.jurusan = { [Sequelize.Op.like]: `%${major}%` };

      const orderBy = [];
      if (sort && ["name", "major"].includes(sort.toLowerCase())) {
        orderBy.push([
          sort === "name" ? "nama" : "jurusan",
          order && order.toLowerCase() === "desc" ? "DESC" : "ASC",
        ]);
      }

      const students = await Student.findAll({ 
        where: query, order: orderBy 
      });
      res.json({ message: "Menampilkan students", data: students });
    } catch (error) {
      res.status(500).json({ 
        message: `Internal Server Error: ${error.message}` 
      });
    }
  };
  store = async (req, res) => {
    try {
      const { nama, nim, email, jurusan } = req.body;
       // Validasi field yang wajib diisi
       const isMissingFields = REQUIRED_DATA.some(field => !req.body[field]);
       if (isMissingFields) {
         throw new Error(`Semua field wajib diisi`);
       }
 
       // Validasi format NIM 
       if (!/^\d{8}$/.test(nim)) {
         throw new Error("Format NIM tidak valid");
       }
 
       // Validasi format email
       if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
         throw new Error("Format email tidak valid");
       }
 
       // Validasi jurusan (contoh: harus terdiri dari huruf)
       if (!/^[a-zA-Z\s]+$/.test(jurusan)) {
         throw new Error("Format jurusan tidak valid");
       }

      const newStudent = await Student.create({ nama, nim, email, jurusan });

      res.json({
        message: 'Menambahkan data student',
        data: newStudent,
      });
    } catch (error) {
      res.status(500).json({ message: error.message });
    }
  };

  update = async (req, res) => {
    try {
      const { nama, nim, email, jurusan } = req.body;
      const missingFields = REQUIRED_DATA.filter((el) => !req.body[el]);

      if (missingFields.length > 0) {
        throw new Error(`Field ${missingFields.join(",")} harus diisi`);
      }

      const studentId = req.params.id;
      const student = await Student.findByPk(studentId);

      if (!student) {
        throw new Error("Student not found");
      }

      await student.update({ nama, nim, email, jurusan });

      res.json({
        message: 'Memperbarui data student',
        data: student,
      });
    } catch (error) {
      res.status(500).json({ message: error.message });
    }
  };

  destroy = async (req, res) => {
    try {
      const studentId = req.params.id;
      const student = await Student.findByPk(studentId);

      if (!student) {
        throw new Error("Student not found");
      }

      await student.destroy();

      res.json({
        message: 'Menghapus data student',
        data: student,
      });
    } catch (error) {
      res.status(500).json({ message: error.message });
    }
  };
  show = async (req, res) => {
    try {
      const { id } = req.params;

      const student = await Student.findByPk(id);

      if (student) {
        const data = {
          message: "Detail student",
          data: student,
        };

        res.status(200).json(data);
      } else {
        const data = {
          message: "Student not found",
        };

        res.status(404).json(data);
      }
    } catch (error) {
      console.error("Error in show:", error);
      res.status(500).json({ message: "Internal Server Error" });
    }
  }
}

const students = new StudentController();

export default students;
-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 03, 2024 at 06:29 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `counseling_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `advisor`
--

CREATE TABLE `advisor` (
  `email` varchar(40) NOT NULL,
  `pass` varchar(60) NOT NULL,
  `name` varchar(20) NOT NULL,
  `last_name` varchar(20) NOT NULL,
  `privileges` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `advisor`
--

INSERT INTO `advisor` (`email`, `pass`, `name`, `last_name`, `privileges`) VALUES
('eliana.valenzuela@upr.edu', '$2y$10$7Jn997dBkgv/j8yCZ.u3/OO0RANOu1TOxFZ.nvLzVMoFA.Ok/V6Nq', 'Elian', 'Valenzuela', 1),
('javier@dragonflydrones.com', '$2y$10$pb9Q6FfHB3NlfxCaaTYCM.2m4J2XWscWNQqOpw/wbVR4aIHcBuhVS', 'dji', 'Quinones', 0);

-- --------------------------------------------------------

--
-- Table structure for table `ccom_courses`
--

CREATE TABLE `ccom_courses` (
  `crse_code` varchar(8) NOT NULL,
  `name` varchar(40) NOT NULL,
  `credits` tinyint(1) NOT NULL,
  `type` varchar(10) NOT NULL DEFAULT '0',
  `level` varchar(15) DEFAULT NULL,
  `minor_id` int(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ccom_courses`
--

INSERT INTO `ccom_courses` (`crse_code`, `name`, `credits`, `type`, `level`, `minor_id`) VALUES
('CCOM3001', 'Programación de computadoras I', 5, 'mandatory', 'NULL', NULL),
('CCOM3002', 'Programación de computadoras II', 5, 'mandatory', NULL, NULL),
('CCOM3010', 'Niveles lógicos', 3, 'mandatory', NULL, NULL),
('CCOM3017', 'Seguridad de las computadoras y la inf.', 3, 'mandatory', NULL, NULL),
('CCOM3020', 'Matemáticas discretas', 3, 'mandatory', NULL, NULL),
('CCOM3025', 'Introducción sistemas de computadoras', 3, 'mandatory', NULL, NULL),
('CCOM3027', 'Programación orientada a objetos', 3, 'elective', 'intermediate', NULL),
('CCOM3035', 'Organización de computadoras', 3, 'mandatory', NULL, NULL),
('CCOM3036', 'Programación visual', 3, 'elective', 'intermediate', NULL),
('CCOM3041', 'Sistemas operativos', 3, 'mandatory', NULL, NULL),
('CCOM3042', 'Arquitectura de computadoras', 3, 'elective', 'advanced', NULL),
('CCOM3115', 'Aplicaciones básicas microprocesadores', 3, 'elective', 'advanced', NULL),
('CCOM3135', 'Temas en ciencias de cómputos', 3, 'elective', 'advanced', NULL),
('CCOM3985', 'Investigación subgraduada', 2, 'elective', 'variable', NULL),
('CCOM4005', 'Estructura de datos', 3, 'mandatory', NULL, NULL),
('CCOM4006', 'Diseño y análisis de algoritmos', 3, 'mandatory', NULL, NULL),
('CCOM4007', 'Estadística con aplicación a ciencias..', 4, 'mandatory', NULL, NULL),
('CCOM4018', 'Redes de computadoras', 3, 'elective', 'advanced', NULL),
('CCOM4019', 'Programación web con PhP/MySQL', 3, 'elective', 'advanced', 1),
('CCOM4025', 'Organización de lenguajes de prog.', 3, 'mandatory', NULL, NULL),
('CCOM4065', 'Álgebra lineal', 3, 'mandatory', NULL, NULL),
('CCOM4075', 'Ingeniería de programación', 3, 'mandatory', NULL, NULL),
('CCOM4095', 'Proyecto de ingeniería de programación', 3, 'mandatory', NULL, NULL),
('CCOM4115', 'Diseño de base de datos', 3, 'mandatory', NULL, NULL),
('CCOM4125', 'Inteligencia artificial', 3, 'elective', 'advanced', NULL),
('CCOM4135', 'Introducción al diseño de compiladores', 3, 'elective', 'advanced', NULL),
('CCOM4201', 'Teoría de grafos', 3, 'mandatory', NULL, NULL),
('CCOM4305', 'Introducción al diseño páginas para web', 3, 'elective', 'intermediate', 1),
('CCOM4306', 'Creación, manejo, opt. de gráficos', 3, 'elective', 'intermediate', 1),
('CCOM4307', 'Mantenimiento de computadoras', 4, 'elective', 'advanced', NULL),
('CCOM4401', 'Desarrollo de aplicaciones móviles', 3, 'elective', 'advanced', NULL),
('CCOM4420', 'Aplicaciones de comp. en la nube', 3, 'elective', 'advanced', NULL),
('CCOM4440', 'Python (Introducción a videojuegos)', 3, 'elective', 'intermediate', NULL),
('CCOM4501', 'Introducción a la robótica', 4, 'elective', 'intermediate', NULL),
('CCOM4991', 'Estudio independiente I', 3, 'elective', 'advanced', NULL),
('CCOM4992', 'Estudio independiente II', 3, 'elective', 'advanced', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ccom_requirements`
--

CREATE TABLE `ccom_requirements` (
  `crse_code` varchar(8) NOT NULL,
  `cohort_year` varchar(4) NOT NULL,
  `type` varchar(3) NOT NULL,
  `req_crse_code` varchar(8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ccom_requirements`
--

INSERT INTO `ccom_requirements` (`crse_code`, `cohort_year`, `type`, `req_crse_code`) VALUES
('CCOM3002', '2017', 'pre', 'CCOM3001'),
('CCOM3035', '2017', 'pre', 'CCOM3025'),
('CCOM3041', '2017', 'pre', 'CCOM3035'),
('CCOM3041', '2017', 'pre', 'CCOM4005'),
('CCOM4005', '2017', 'pre', 'CCOM3002'),
('CCOM4005', '2017', 'pre', 'MATE3171'),
('CCOM4005', '2017', 'co', 'CCOM3020'),
('CCOM3020', '2017', 'pre', 'MATE3171'),
('MATE3172', '2017', 'pre', 'MATE3171'),
('CCOM4006', '2017', 'pre', 'CCOM4005'),
('CCOM4006', '2017', 'pre', 'CCOM3020'),
('MATE3031', '2017', 'pre', 'MATE3172'),
('CCOM4025', '2017', 'pre', 'CCOM4005'),
('CCOM4115', '2017', 'pre', 'CCOM4025'),
('CCOM4007', '2017', 'pre', 'CCOM3020'),
('CCOM4007', '2017', 'pre', 'MATE3172'),
('CCOM4065', '2017', 'pre', 'CCOM3002'),
('CCOM4065', '2017', 'pre', 'MATE3031'),
('CCOM4075', '2017', 'pre', 'CCOM3041'),
('CCOM4075', '2017', 'pre', 'CCOM4115'),
('CCOM4075', '2017', 'pre', 'CCOM4006'),
('CCOM4075', '2017', 'pre', 'CCOM4007'),
('CCOM4095', '2017', 'pre', 'CCOM4075'),
('CCOM3002', '2022', 'pre', 'CCOM3001');

-- --------------------------------------------------------

--
-- Table structure for table `cohort`
--

CREATE TABLE `cohort` (
  `cohort_year` varchar(4) NOT NULL,
  `crse_code` varchar(8) NOT NULL,
  `crse_year` tinyint(1) NOT NULL,
  `crse_semester` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cohort`
--

INSERT INTO `cohort` (`cohort_year`, `crse_code`, `crse_year`, `crse_semester`) VALUES
('2017', 'CCOM3010', 1, 1),
('2017', 'CCOM3025', 1, 1),
('2017', 'MATE3171', 1, 1),
('2017', 'CCOM3002', 1, 2),
('2017', 'CCOM3015', 1, 2),
('2017', 'CCOM3035', 1, 2),
('2017', 'MATE3172', 1, 2),
('2017', 'CCOM4005', 2, 1),
('2017', 'MATE3031', 2, 1),
('2017', 'CCOM3020', 2, 1),
('2017', 'CIBI3001', 2, 1),
('2017', 'CCOM4006', 2, 2),
('2017', 'CCOM4007', 2, 2),
('2017', 'CCOM4065', 2, 2),
('2017', 'CIBI3002', 2, 2),
('2017', 'FISI3013', 3, 1),
('2017', 'CCOM3041', 3, 1),
('2017', 'CCOM4025', 3, 1),
('2017', 'FISI3012', 3, 2),
('2017', 'FISI3014', 3, 2),
('2017', 'CCOM4115', 3, 2),
('2017', 'CCOM4075', 4, 1),
('2017', 'CCOM4095', 4, 2),
('2017', 'CCOM3001', 1, 1),
('2017', 'INGL3101', 1, 1),
('2017', 'INGL3113', 1, 1),
('2017', 'INGL3102', 1, 2),
('2017', 'INGL3114', 1, 2),
('2017', 'ESPA3101', 2, 1),
('2017', 'ESPA3102', 2, 2),
('2017', 'FISI3011', 3, 1),
('2017', 'ESPA3208', 3, 1),
('2017', 'INGL3015', 3, 2),
('2017', 'FREEXXXX', 3, 2),
('2017', 'HUMAXXXX', 4, 1),
('2017', 'HUMAXXXX', 4, 2),
('2017', 'CISOXXXX', 4, 1),
('2017', 'CISOXXXX', 4, 2),
('2017', 'CCOMXXXX', 3, 2),
('2017', 'CCOMXXXX', 4, 1),
('2017', 'CCOMXXXX', 4, 1),
('2017', 'FREEXXXX', 4, 2),
('2017', 'FREEXXXX', 4, 2),
('2022', 'CCOM3017', 2, 1),
('2022', 'CCOM3025', 1, 1),
('2022', 'MATE3171', 1, 1),
('2022', 'CCOM3035', 1, 2),
('2022', 'MATE3172', 1, 2),
('2022', 'CCOM4005', 2, 1),
('2022', 'MATE3031', 2, 1),
('2022', 'CIBI3001', 2, 1),
('2022', 'CCOM4006', 2, 2),
('2022', 'CCOM4007', 2, 2),
('2022', 'CCOM4065', 2, 2),
('2022', 'CIBI3002', 2, 2),
('2022', 'FISI3013', 3, 1),
('2022', 'CCOM3041', 3, 1),
('2022', 'CCOM4025', 3, 1),
('2022', 'FISI3012', 3, 2),
('2022', 'FISI3014', 3, 2),
('2022', 'CCOM4115', 3, 2),
('2022', 'CCOM4075', 4, 1),
('2022', 'CCOM4095', 4, 2),
('2022', 'CCOM3001', 1, 1),
('2022', 'CCOM3002', 1, 2),
('2022', 'INGL3101', 1, 1),
('2022', 'INGL3113', 1, 1),
('2022', 'INGL3102', 1, 2),
('2022', 'INGL3114', 1, 2),
('2022', 'ESPA3101', 1, 1),
('2022', 'ESPA3102', 1, 2),
('2022', 'FISI3011', 3, 1),
('2022', 'ESPA3208', 3, 1),
('2022', 'INGL3015', 2, 2),
('2022', 'FREEXXXX', 3, 2),
('2022', 'HUMAXXXX', 4, 1),
('2022', 'HUMAXXXX', 4, 2),
('2022', 'CISOXXXX', 4, 1),
('2022', 'CISOXXXX', 4, 2),
('2022', 'CCOMXXXX', 3, 2),
('2022', 'CCOMXXXX', 4, 1),
('2022', 'FREEXXXX', 4, 2),
('2022', 'FREEXXXX', 4, 2),
('2022', 'CCOM3020', 2, 1),
('2022', 'CCOM4201', 3, 2),
('2022', 'FREEXXXX', 4, 1),
('2022', 'FREEXXXX', 4, 1);

-- --------------------------------------------------------

--
-- Table structure for table `cohort_requirements`
--

CREATE TABLE `cohort_requirements` (
  `cohort_year` varchar(4) NOT NULL,
  `credits_huma` tinyint(2) NOT NULL,
  `credits_ciso` tinyint(2) NOT NULL,
  `credits_dept` tinyint(2) NOT NULL,
  `credits_int` tinyint(2) NOT NULL,
  `credits_free` tinyint(2) NOT NULL,
  `credits_avz` tinyint(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cohort_requirements`
--

INSERT INTO `cohort_requirements` (`cohort_year`, `credits_huma`, `credits_ciso`, `credits_dept`, `credits_int`, `credits_free`, `credits_avz`) VALUES
('2017', 21, 21, 7, 3, 14, 4);

-- --------------------------------------------------------

--
-- Table structure for table `dummy_courses`
--

CREATE TABLE `dummy_courses` (
  `crse_code` varchar(8) NOT NULL,
  `name` varchar(50) NOT NULL,
  `credits` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dummy_courses`
--

INSERT INTO `dummy_courses` (`crse_code`, `name`, `credits`) VALUES
('HUMAXXXX', 'Electiva General de HUMA', 3),
('CISOXXXX', 'Electiva General de CISO', 3),
('CCOMINTX', 'Electiva Intermedia de CCOM', 3),
('CCOMAVZX', 'Electiva Avanzada de CCOM', 3),
('FREEXXXX', 'Electiva Libre', 3),
('CCOMXXXX', 'Electiva Departamental', 3);

-- --------------------------------------------------------

--
-- Table structure for table `general_courses`
--

CREATE TABLE `general_courses` (
  `crse_code` varchar(8) NOT NULL,
  `name` varchar(40) NOT NULL,
  `credits` tinyint(1) NOT NULL,
  `required` tinyint(1) NOT NULL DEFAULT 0,
  `type` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `general_courses`
--

INSERT INTO `general_courses` (`crse_code`, `name`, `credits`, `required`, `type`) VALUES
('ESPA3101', 'Español básico I', 3, 1, 'ESPA'),
('ESPA3102', 'Español básico II', 3, 1, 'ESPA'),
('ESPA3208', 'Redacción y estilo', 3, 1, 'ESPA'),
('ESPA3003', 'Fundamentos de lengua y discurso I', 3, 0, 'ESPA'),
('ESPA3004', 'Fundamentos de lengua y discurso II', 3, 0, 'ESPA'),
('INGL3101', 'Basic English I', 3, 1, 'INGL'),
('INGL3102', 'Basic English II', 3, 1, 'INGL'),
('INGL3103', 'Intermediate English I', 3, 0, 'INGL'),
('INGL3104', 'Intermediate English II', 3, 0, 'INGL'),
('INGL3011', 'Honor\'s English I', 3, 0, 'INGL'),
('INGL3012', 'Honor\'s English II', 3, 0, 'INGL'),
('INGL3015', 'English for Science and Tecnology I', 3, 1, 'INGL'),
('MATE3171', 'Pre-Cálculo I', 3, 1, 'MATE'),
('MATE3172', 'Pre-Cálculo II', 3, 1, 'MATE'),
('MATE3031', 'Cálculo I', 4, 1, 'MATE'),
('CIBI3001', 'Int. Cs. Biológicas I', 3, 1, 'CIBI'),
('CIBI3002', 'Int. Cs. Biológicas II', 3, 1, 'CIBI'),
('FISI3011', 'Física Universitaria I', 3, 1, 'FISI'),
('FISI3013', 'Laboratorio Física Univ. I', 1, 1, 'FISI'),
('FISI3012', 'Física Universitaria II', 3, 1, 'FISI'),
('FISI3014', 'Laboratorio Física Univ. II', 1, 1, 'FISI'),
('CISO3121', 'Introducción a las Ciencias Sociales I', 3, 1, 'CISO'),
('CISO3122', 'Introducción a las Ciencias Sociales II', 3, 1, 'CISO'),
('INGL3113', 'Práct. Oral Inglés Básico I', 0, 1, 'INGL'),
('INGL3114', 'Práct. Oral Inglés Básico Ii', 0, 1, 'INGL'),
('HUMA3101', 'Cultura Occidental I', 3, 0, 'HUMA'),
('CISO3121', 'Introducción Ciencias Sociales', 3, 0, 'CISO'),
('GEOG3155', 'Elementos de Geografía', 3, 0, 'CISO'),
('ASTR3009', 'Astronomía General', 3, 0, 'FREE'),
('HUMA3102', 'Cultura Occidental II', 3, 0, 'HUMA');

-- --------------------------------------------------------

--
-- Table structure for table `general_requirements`
--

CREATE TABLE `general_requirements` (
  `crse_code` varchar(8) NOT NULL,
  `cohort_year` varchar(4) NOT NULL,
  `type` varchar(3) NOT NULL,
  `req_crse_code` varchar(8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `general_requirements`
--

INSERT INTO `general_requirements` (`crse_code`, `cohort_year`, `type`, `req_crse_code`) VALUES
('INGL3102', '2017', 'pre', 'INGL3101'),
('INGL3101', '2017', 'co', 'INGL3113'),
('MATE3172', '2017', 'pre', 'MATE3171'),
('INGL3102', '2017', 'co', 'INGL3114'),
('MATE3031', '2017', 'pre', 'MATE3172'),
('ESPA3102', '2017', 'pre', 'ESPA3101'),
('CIBI3002', '2017', 'pre', 'CIBI3001'),
('FISI3011', '2017', 'co', 'FISI3013'),
('FISI3012', '2017', 'co', 'FISI3014'),
('ESPA3208', '2017', 'pre', 'ESPA3102'),
('INGL3015', '2017', 'pre', 'INGL3102'),
('FISI3012', '2017', 'pre', 'FISI3011');

-- --------------------------------------------------------

--
-- Table structure for table `minor`
--

CREATE TABLE `minor` (
  `ID` smallint(3) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `minor`
--

INSERT INTO `minor` (`ID`, `name`) VALUES
(1, 'Web Design');

-- --------------------------------------------------------

--
-- Table structure for table `offer`
--

CREATE TABLE `offer` (
  `crse_code` varchar(8) NOT NULL,
  `term` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `offer`
--

INSERT INTO `offer` (`crse_code`, `term`) VALUES
('XXXX', 'C32'),
('CCOM3001', 'C32'),
('CCOM3002', 'C32'),
('CCOM3035', 'C32'),
('CCOM4006', 'C32'),
('CCOM4007', 'C32'),
('CCOM4065', 'C32'),
('CCOM4115', 'C32'),
('CCOM3017', 'C32'),
('CCOM4095', 'C32'),
('CCOM4305', 'C32'),
('CCOM4420', 'C32');

-- --------------------------------------------------------

--
-- Table structure for table `recommended_courses`
--

CREATE TABLE `recommended_courses` (
  `student_num` int(9) NOT NULL,
  `crse_code` varchar(8) NOT NULL,
  `term` varchar(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `recommended_courses`
--

INSERT INTO `recommended_courses` (`student_num`, `crse_code`, `term`) VALUES
(840182717, 'CCOM4095', 'C32');

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `student_num` int(9) NOT NULL,
  `email` varchar(40) NOT NULL,
  `name1` varchar(15) NOT NULL,
  `name2` varchar(15) DEFAULT NULL,
  `last_name1` varchar(20) NOT NULL,
  `last_name2` varchar(20) NOT NULL,
  `dob` date NOT NULL,
  `conducted_counseling` tinyint(1) NOT NULL DEFAULT 0,
  `minor` tinyint(3) NOT NULL DEFAULT 0,
  `cohort_year` varchar(4) NOT NULL,
  `status` varchar(8) NOT NULL DEFAULT 'Activo',
  `edited_date` date NOT NULL,
  `grad_term` varchar(3) DEFAULT NULL,
  `student_note` varchar(150) DEFAULT NULL,
  `admin_note` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`student_num`, `email`, `name1`, `name2`, `last_name1`, `last_name2`, `dob`, `conducted_counseling`, `minor`, `cohort_year`, `status`, `edited_date`, `grad_term`, `student_note`, `admin_note`) VALUES
(840182717, 'javier.quinones3@upr.edu', 'Javier', 'L', 'Quinones', 'Galan', '2000-01-05', 127, 1, '2017', 'Activo', '2024-04-02', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `student_courses`
--

CREATE TABLE `student_courses` (
  `student_num` int(9) NOT NULL,
  `crse_code` varchar(8) NOT NULL,
  `credits` tinyint(1) NOT NULL,
  `type` varchar(10) NOT NULL,
  `crse_grade` varchar(2) NOT NULL,
  `crse_status` varchar(10) NOT NULL,
  `term` varchar(3) NOT NULL,
  `equivalencia` varchar(100) NOT NULL,
  `convalidacion` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_courses`
--

INSERT INTO `student_courses` (`student_num`, `crse_code`, `credits`, `type`, `crse_grade`, `crse_status`, `term`, `equivalencia`, `convalidacion`) VALUES
(840182717, 'CCOM3001', 5, 'mandatory', 'A', 'P', 'XXX', 'INGE3011[2]+INGE3016[3]', ''),
(840182717, 'CCOM3002', 5, 'mandatory', 'A', 'P', 'XXX', '', ''),
(840182717, 'CCOM3010', 3, 'mandatory', 'A', 'P', 'XXX', '', ''),
(840182717, 'CCOM3015', 3, 'mandatory', 'A', 'P', 'XXX', '', ''),
(840182717, 'CCOM3020', 3, 'mandatory', 'A', 'P', 'XXX', '', ''),
(840182717, 'CCOM3025', 3, 'mandatory', 'A', 'P', 'XXX', '', ''),
(840182717, 'CCOM3035', 3, 'mandatory', 'A', 'P', 'XXX', '', ''),
(840182717, 'CCOM3041', 3, 'mandatory', 'A', 'P', 'XXX', '', ''),
(840182717, 'CCOM4005', 3, 'mandatory', 'A', 'P', 'XXX', '', ''),
(840182717, 'CCOM4006', 3, 'mandatory', 'A', 'P', 'XXX', '', ''),
(840182717, 'CCOM4007', 4, 'mandatory', 'A', 'P', 'XXX', '', ''),
(840182717, 'CCOM4025', 3, 'mandatory', 'A', 'P', 'XXX', '', ''),
(840182717, 'CCOM4065', 3, 'mandatory', 'A', 'P', 'XXX', '', ''),
(840182717, 'CCOM3015', 3, 'mandatory', 'A', 'P', 'XXX', '', ''),
(840182717, 'CCOM4115', 3, 'mandatory', 'A', 'P', 'XXX', '', ''),
(840182717, 'ESPA3101', 3, 'elective', 'P', 'P', 'XXX', '', ''),
(840182717, 'ESPA3102', 3, 'elective', 'P', 'P', 'XXX', '', ''),
(840182717, 'ESPA3208', 3, 'elective', 'A', 'P', 'XXX', '', ''),
(840182717, 'INGL3101', 3, 'elective', 'P', 'P', 'XXX', '', ''),
(840182717, 'INGL3102', 3, 'elective', 'P', 'P', 'XXX', '', ''),
(840182717, 'INGL3015', 3, 'elective', 'A', 'P', 'XXX', '', ''),
(840182717, 'MATE3171', 3, 'elective', 'A', 'P', 'XXX', '', ''),
(840182717, 'MATE3172', 3, 'elective', 'A', 'P', 'XXX', '', ''),
(840182717, 'MATE3031', 4, 'elective', 'B', 'P', 'XXX', '', ''),
(840182717, 'MUSI3225', 3, 'elective', 'A', 'P', 'XXX', '', ''),
(840182717, 'HIST3241', 3, 'elective', 'A', 'P', 'XXX', '', ''),
(840182717, 'CISO3121', 3, 'elective', 'B', 'P', 'XXX', '', ''),
(840182717, 'ECON3021', 3, 'elective', 'A', 'P', 'XXX', '', ''),
(840182717, 'CIBI3001', 3, 'elective', 'A', 'P', 'XXX', '', ''),
(840182717, 'CIBI3002', 3, 'elective', 'A', 'P', 'XXX', '', ''),
(840182717, 'ESPA3101', 3, 'elective', 'P', 'P', 'XXX', '', ''),
(840182717, 'FISI3011', 3, 'elective', 'B', 'P', 'XXX', 'FISI3171', ''),
(840182717, 'FISI3013', 1, 'elective', 'A', 'P', 'XXX', 'FISI3173', ''),
(840182717, 'FISI3012', 3, 'elective', 'B', 'P', 'XXX', '', ''),
(840182717, 'FISI3014', 1, 'elective', 'A', 'P', 'XXX', '', ''),
(840182717, 'INGL3221', 3, 'elective', 'A', 'P', 'XXX', '', ''),
(840182717, 'MUSI3175', 3, 'elective', 'A', 'P', 'XXX', '', ''),
(840182717, 'INGL3011', 3, 'elective', 'A', 'P', 'XXX', '', ''),
(840182717, 'INGL3012', 3, 'elective', 'A', 'P', 'XXX', '', ''),
(840182717, 'CCOM4305', 3, 'elective', 'A', 'P', 'XXX', '', ''),
(840182717, 'CCOM4306', 3, 'elective', 'A', 'P', 'XXX', '', ''),
(840182717, 'CCOM4019', 3, 'elective', 'A', 'P', 'XXX', '', ''),
(840182717, 'INGL3238', 3, 'elective', 'A', 'P', 'XXX', '', ''),
(840182717, 'CCOM4991', 3, 'elective', 'A', 'P', 'XXX', '', ''),
(840182717, 'INTD4995', 3, 'elective', 'A', 'P', 'XXX', '', ''),
(840182717, 'CCOM3985', 2, 'elective', 'A', 'P', 'C22', '', ''),
(840182717, 'INTD4995', 3, 'elective', 'A', 'P', 'C22', '', ''),
(840182717, 'CCOM3135', 3, 'elective', 'A', 'P', 'C31', '', ''),
(840182717, 'CCOM4075', 3, 'mandatory', 'A', 'P', 'C31', '', ''),
(840182717, 'CCOM3985', 2, 'elective', 'A', 'P', 'C31', '', ''),
(840182717, 'INTD4995', 3, 'elective', 'A', 'P', 'C31', '', ''),
(840182717, 'CCOM3017', 3, 'mandatory', 'A', 'P', 'C31', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `will_take`
--

CREATE TABLE `will_take` (
  `student_num` varchar(9) NOT NULL,
  `crse_code` varchar(8) NOT NULL,
  `term` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `will_take`
--

INSERT INTO `will_take` (`student_num`, `crse_code`, `term`) VALUES
('840182717', 'CCOM4095', 'C32'),
('840182717', 'INTD4995', 'C32'),
('840182717', 'CCOM3985', 'C32');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ccom_courses`
--
ALTER TABLE `ccom_courses`
  ADD PRIMARY KEY (`crse_code`);

--
-- Indexes for table `cohort_requirements`
--
ALTER TABLE `cohort_requirements`
  ADD PRIMARY KEY (`cohort_year`);

--
-- Indexes for table `minor`
--
ALTER TABLE `minor`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`student_num`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `minor`
--
ALTER TABLE `minor`
  MODIFY `ID` smallint(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

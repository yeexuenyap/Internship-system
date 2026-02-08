-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 07, 2026 at 07:59 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `internship_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `academic_requests`
--

CREATE TABLE `academic_requests` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `academic_id` int(11) NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `academic_requests`
--

INSERT INTO `academic_requests` (`id`, `student_id`, `academic_id`, `status`, `created_at`) VALUES
(4, 12, 15, 'approved', '2026-02-07 03:00:12'),
(5, 17, 10, 'approved', '2026-02-07 05:59:15');

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `internship_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `status` enum('present','absent') NOT NULL,
  `marked_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`id`, `student_id`, `internship_id`, `date`, `status`, `marked_at`) VALUES
(9, 17, 13, '2025-06-07', 'present', '2026-02-07 03:03:13'),
(10, 17, 13, '2025-06-08', 'present', '2026-02-07 03:03:26'),
(11, 17, 13, '2025-06-09', 'present', '2026-02-07 03:03:36'),
(12, 17, 13, '2025-06-10', 'present', '2026-02-07 03:03:46'),
(13, 17, 13, '2025-06-11', 'present', '2026-02-07 03:07:36'),
(14, 17, 13, '2025-06-12', 'present', '2026-02-07 03:07:36'),
(15, 17, 13, '2025-06-13', 'absent', '2026-02-07 03:07:36'),
(16, 17, 13, '2025-06-14', 'present', '2026-02-07 03:07:36'),
(17, 17, 13, '2025-06-15', 'present', '2026-02-07 03:07:36'),
(18, 17, 13, '2025-06-16', 'present', '2026-02-07 03:07:36'),
(19, 17, 13, '2025-06-17', 'present', '2026-02-07 03:07:36'),
(20, 17, 13, '2025-06-18', 'present', '2026-02-07 03:07:36'),
(21, 17, 13, '2025-06-19', 'absent', '2026-02-07 03:07:36'),
(22, 17, 13, '2025-06-20', 'present', '2026-02-07 03:07:36'),
(23, 17, 13, '2025-06-30', 'present', '2026-02-07 03:07:36'),
(24, 17, 13, '2025-07-01', 'present', '2026-02-07 03:07:36'),
(25, 17, 13, '2025-07-02', 'present', '2026-02-07 03:07:36'),
(26, 17, 13, '2025-07-03', 'absent', '2026-02-07 03:07:36'),
(27, 17, 13, '2025-07-04', 'present', '2026-02-07 03:07:36'),
(28, 17, 13, '2025-07-07', 'present', '2026-02-07 03:07:36'),
(29, 17, 13, '2025-07-08', 'present', '2026-02-07 03:07:36'),
(30, 17, 13, '2025-07-09', 'present', '2026-02-07 03:07:36'),
(31, 17, 13, '2025-07-10', 'present', '2026-02-07 03:07:36'),
(32, 17, 13, '2025-08-01', 'present', '2026-02-07 03:07:36'),
(33, 17, 13, '2025-08-04', 'absent', '2026-02-07 03:07:36'),
(34, 17, 13, '2025-08-05', 'present', '2026-02-07 03:07:36'),
(35, 17, 13, '2025-08-06', 'present', '2026-02-07 03:07:36'),
(36, 17, 13, '2025-09-15', 'present', '2026-02-07 03:07:36'),
(37, 17, 13, '2025-09-16', 'absent', '2026-02-07 03:07:36'),
(38, 17, 13, '2025-10-10', 'present', '2026-02-07 03:07:36'),
(39, 17, 13, '2025-10-13', 'absent', '2026-02-07 03:07:36'),
(40, 17, 13, '2025-11-11', 'present', '2026-02-07 03:07:36'),
(41, 17, 13, '2025-11-12', 'present', '2026-02-07 03:07:36'),
(42, 17, 13, '2025-11-13', 'absent', '2026-02-07 03:07:36'),
(43, 17, 13, '2025-12-25', 'absent', '2026-02-07 03:07:36'),
(44, 17, 13, '2025-12-31', 'present', '2026-02-07 03:07:36'),
(45, 17, 13, '2026-01-01', 'absent', '2026-02-07 03:07:36'),
(46, 17, 13, '2026-01-15', 'present', '2026-02-07 03:07:36'),
(47, 17, 13, '2026-01-20', 'present', '2026-02-07 03:07:36'),
(48, 17, 13, '2026-01-25', 'absent', '2026-02-07 03:07:36'),
(49, 17, 13, '2026-02-01', 'present', '2026-02-07 03:07:36'),
(50, 17, 13, '2026-02-02', 'present', '2026-02-07 03:07:36'),
(51, 17, 13, '2026-02-03', 'present', '2026-02-07 03:07:36'),
(52, 17, 13, '2026-02-04', 'present', '2026-02-07 03:07:36'),
(53, 17, 13, '2026-02-05', 'absent', '2026-02-07 03:07:36'),
(54, 17, 13, '2026-02-06', 'present', '2026-02-07 03:07:36'),
(55, 17, 13, '2026-02-07', 'present', '2026-02-07 03:07:36'),
(56, 12, 10, '2026-02-02', 'present', '2026-02-07 06:28:23'),
(57, 12, 10, '2026-02-03', 'present', '2026-02-07 06:28:29'),
(58, 12, 10, '2026-02-04', 'present', '2026-02-07 06:28:34'),
(59, 12, 10, '2026-02-05', 'present', '2026-02-07 06:28:38'),
(60, 12, 10, '2026-02-06', 'present', '2026-02-07 06:28:43');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `internship_id` int(11) NOT NULL,
  `feedback_date` date NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `student_id`, `internship_id`, `feedback_date`, `content`, `created_at`) VALUES
(117, 17, 13, '2025-06-09', 'Excellent attention to safety protocols. Completed all safety training successfully.', '2026-02-07 05:54:31'),
(118, 17, 13, '2025-06-10', 'Quick learner on lab equipment. Demonstrated proper handling techniques.', '2026-02-07 05:54:31'),
(119, 17, 13, '2025-06-11', 'Good understanding of aseptic techniques. Sample collection was contamination-free.', '2026-02-07 05:54:31'),
(120, 17, 13, '2025-06-12', 'First sample processing successful. All samples properly processed and stored.', '2026-02-07 05:54:31'),
(121, 17, 13, '2025-06-13', 'Microscopy skills developing well. Able to identify different cell types accurately.', '2026-02-07 05:54:31'),
(122, 17, 13, '2025-06-16', 'Cell culture technique is good. Maintained sterile conditions throughout.', '2026-02-07 05:54:31'),
(123, 17, 13, '2025-06-17', 'DNA extraction yield and quality are excellent. Nanodrop readings within expected range.', '2026-02-07 05:54:31'),
(124, 17, 13, '2025-06-18', 'PCR setup shows good technical skills. All controls worked as expected.', '2026-02-07 05:54:31'),
(125, 17, 13, '2025-06-19', 'Gel electrophoresis performed correctly. Bands are clear and well-separated.', '2026-02-07 05:54:31'),
(126, 17, 13, '2025-06-20', 'Electronic lab notebook entries are detailed and organized. Good documentation practice.', '2026-02-07 05:54:31'),
(127, 17, 13, '2025-06-23', 'Basic statistical understanding is good. Able to perform t-tests correctly.', '2026-02-07 05:54:31'),
(128, 17, 13, '2025-06-24', 'Protein extraction successful. BCA assay shows good protein concentration.', '2026-02-07 05:54:31'),
(129, 17, 13, '2025-06-25', 'Western blot preparation done correctly. Gel polymerization successful.', '2026-02-07 05:54:31'),
(130, 17, 13, '2025-06-26', 'Western blot transfer and blocking completed without issues.', '2026-02-07 05:54:31'),
(131, 17, 13, '2025-06-27', 'ImageJ analysis done accurately. Band intensity measurements are reliable.', '2026-02-07 05:54:31'),
(132, 17, 13, '2025-06-30', 'Good understanding of research project objectives. Literature review was thorough.', '2026-02-07 05:54:31'),
(133, 17, 13, '2025-07-01', 'ELISA technique mastered quickly. Standard curve shows excellent linearity.', '2026-02-07 05:54:31'),
(134, 17, 13, '2025-07-02', 'Processed 50 samples efficiently. All ELISA readings within expected range.', '2026-02-07 05:54:31'),
(135, 17, 13, '2025-07-03', 'Statistical analysis of ELISA data is accurate. Good use of GraphPad Prism.', '2026-02-07 05:54:31'),
(136, 17, 13, '2025-07-04', 'MTT assay setup correct. Drug concentrations properly prepared.', '2026-02-07 05:54:31'),
(137, 17, 13, '2025-07-07', 'IC50 calculations are accurate. Shows good understanding of dose-response curves.', '2026-02-07 05:54:31'),
(138, 17, 13, '2025-07-08', 'Flow cytometry training completed. Understands basic principles of gating.', '2026-02-07 05:54:31'),
(139, 17, 13, '2025-07-09', 'Apoptosis assay results are clear. Able to distinguish live, early, and late apoptotic cells.', '2026-02-07 05:54:31'),
(140, 17, 13, '2025-07-10', 'Clinical data collection done systematically. Maintained patient confidentiality.', '2026-02-07 05:54:31'),
(141, 17, 13, '2025-07-11', 'Statistical correlation analysis shows good understanding of research methodology.', '2026-02-07 05:54:31'),
(142, 17, 13, '2025-07-14', 'RNA extraction quality excellent. RIN values above 8.0 for all samples.', '2026-02-07 05:54:31'),
(143, 17, 13, '2025-07-15', 'cDNA synthesis successful. Reverse transcription efficiency good.', '2026-02-07 05:54:31'),
(144, 17, 13, '2025-07-16', 'qPCR setup perfect. Triplicates show good technical reproducibility.', '2026-02-07 05:54:31'),
(145, 17, 13, '2025-07-17', 'ΔΔCt calculations accurate. Gene expression analysis done correctly.', '2026-02-07 05:54:31'),
(146, 17, 13, '2025-07-18', 'Literature review shows good critical thinking skills. Identified relevant papers.', '2026-02-07 05:54:31'),
(147, 17, 13, '2025-07-21', 'Lab meeting preparation thorough. Presentation slides are clear and professional.', '2026-02-07 05:54:31'),
(148, 17, 13, '2025-07-22', 'Presentation delivery was confident. Answered questions knowledgeably.', '2026-02-07 05:54:31'),
(149, 17, 13, '2025-07-23', 'IHC technique learning progressing well. Antigen retrieval done correctly.', '2026-02-07 05:54:31'),
(150, 17, 13, '2025-07-24', 'IHC scoring consistent with supervisor assessment. Good observational skills.', '2026-02-07 05:54:31'),
(151, 17, 13, '2025-07-25', 'Data organization is systematic. Database structure is logical and searchable.', '2026-02-07 05:54:31'),
(152, 17, 13, '2025-07-28', 'Quality control procedures followed meticulously. All equipment within calibration limits.', '2026-02-07 05:54:31'),
(153, 17, 13, '2025-07-29', 'Research ethics certification completed. Understands importance of ethical research.', '2026-02-07 05:54:31'),
(154, 17, 13, '2025-07-30', 'Sample inventory management improved lab organization. System is now more efficient.', '2026-02-07 05:54:31'),
(155, 17, 13, '2025-07-31', 'Monthly review shows excellent progress. August goals are ambitious but achievable.', '2026-02-07 05:54:31'),
(156, 17, 13, '2025-08-01', 'Independent project design shows initiative. Research question is relevant.', '2026-02-07 05:54:31'),
(157, 17, 13, '2025-08-04', 'Cell line maintenance perfect. All lines healthy with good growth rates.', '2026-02-07 05:54:31'),
(158, 17, 13, '2025-08-05', 'Drug treatment experiment designed well. Concentrations cover appropriate range.', '2026-02-07 05:54:31'),
(159, 17, 13, '2025-08-06', 'Protein harvest timing optimal. Samples ready for resistance marker analysis.', '2026-02-07 05:54:31'),
(160, 17, 13, '2025-08-07', 'RNA sequencing prep meets quality standards. Samples suitable for NGS.', '2026-02-07 05:54:31'),
(161, 17, 13, '2025-08-08', 'Data mining from public databases shows good bioinformatics understanding.', '2026-02-07 05:54:31'),
(162, 17, 13, '2025-09-01', 'Bioinformatics skills developing quickly. Able to perform basic sequence analysis.', '2026-02-07 05:54:31'),
(163, 17, 13, '2025-09-02', 'Advanced statistical analysis done correctly. ANOVA assumptions checked properly.', '2026-02-07 05:54:31'),
(164, 17, 13, '2025-09-03', 'R programming basics grasped well. Able to write simple scripts for data analysis.', '2026-02-07 05:54:31'),
(165, 17, 13, '2025-09-04', 'Data visualization skills excellent. Graphs are publication-ready.', '2026-02-07 05:54:31'),
(166, 17, 13, '2025-09-05', 'Clinical trial analysis shows understanding of real-world data challenges.', '2026-02-07 05:54:31'),
(167, 17, 13, '2025-10-01', 'Methods section draft is detailed and reproducible. Good scientific writing.', '2026-02-07 05:54:31'),
(168, 17, 13, '2025-10-02', 'Results section clear and well-organized. Tables and figures are informative.', '2026-02-07 05:54:31'),
(169, 17, 13, '2025-10-03', 'Statistical review passed. All analyses are methodologically sound.', '2026-02-07 05:54:31'),
(170, 17, 13, '2025-10-06', 'Figure preparation skills excellent. Images meet journal requirements.', '2026-02-07 05:54:31'),
(171, 17, 13, '2025-10-07', 'Literature citation management professional. Reference list is complete.', '2026-02-07 05:54:31'),
(172, 17, 13, '2025-10-08', 'Abstract well-written. Concise yet comprehensive.', '2026-02-07 05:54:31'),
(173, 17, 13, '2025-10-09', 'Peer review practice shows good critical evaluation skills.', '2026-02-07 05:54:31'),
(174, 17, 13, '2025-11-03', 'Mass spectrometry training completed. Understands basic principles of proteomics.', '2026-02-07 05:54:31'),
(175, 17, 13, '2025-11-04', 'Sample preparation for proteomics meets technical requirements.', '2026-02-07 05:54:31'),
(176, 17, 13, '2025-11-05', 'Metabolomics concepts understood. Aware of sample preparation challenges.', '2026-02-07 05:54:31'),
(177, 17, 13, '2025-11-06', 'CRISPR technology overview shows understanding of cutting-edge techniques.', '2026-02-07 05:54:31'),
(178, 17, 13, '2025-11-07', 'Single-cell sequencing knowledge good. Understands applications in cancer research.', '2026-02-07 05:54:31'),
(179, 17, 13, '2025-12-01', 'Final data analysis comprehensive. All research questions addressed.', '2026-02-07 05:54:31'),
(180, 17, 13, '2025-12-02', 'Research report writing shows good scientific communication skills.', '2026-02-07 05:54:31'),
(181, 17, 13, '2025-12-03', 'Presentation preparation professional. Content is well-organized.', '2026-02-07 05:54:31'),
(182, 17, 13, '2025-12-04', 'Poster design visually appealing and scientifically accurate.', '2026-02-07 05:54:31'),
(183, 17, 13, '2025-12-05', 'Data archiving done according to best practices. All data secure and accessible.', '2026-02-07 05:54:31'),
(184, 17, 13, '2026-01-05', 'Capstone project design innovative. Personalized medicine focus is timely.', '2026-02-07 05:54:31'),
(185, 17, 13, '2026-01-06', 'Patient-derived sample handling shows clinical relevance understanding.', '2026-02-07 05:54:31'),
(186, 17, 13, '2026-01-07', 'Drug sensitivity testing methodology sound. Results will be clinically useful.', '2026-02-07 05:54:31'),
(187, 17, 13, '2026-01-08', 'Biomarker validation approach rigorous. Includes technical and biological validation.', '2026-02-07 05:54:31'),
(188, 17, 13, '2026-01-09', 'Clinical correlation analysis shows translational research mindset.', '2026-02-07 05:54:31'),
(189, 17, 13, '2026-02-02', 'Final results compilation comprehensive. Conclusions are supported by data.', '2026-02-07 05:54:31'),
(190, 17, 13, '2026-02-03', 'Thesis writing shows excellent scientific writing and critical thinking skills.', '2026-02-07 05:54:31'),
(191, 17, 13, '2026-02-04', 'Career development discussion productive. Shows clear potential for research career.', '2026-02-07 05:54:31'),
(192, 17, 13, '2026-02-05', 'Lab equipment handover complete. All equipment in good working condition.', '2026-02-07 05:54:31'),
(193, 17, 13, '2025-02-06', 'Final presentation rehearsal excellent. Delivery is confident and clear.', '2026-02-07 05:54:31'),
(194, 17, 13, '2026-02-07', 'Outstanding performance throughout internship. Demonstrates strong potential for medical research career. Ready for advanced studies or research position.', '2026-02-07 05:54:31'),
(195, 12, 10, '2026-02-02', 'Excellent start on dashboard development. React + TypeScript setup is clean and well-structured. The responsive grid layout works perfectly on all screen sizes. Suggestions: 1) Add keyboard navigation support for sidebar, 2) Consider using CSS variables for theming instead of hardcoded colors, 3) Implement proper focus states for accessibility.', '2026-02-07 06:35:40'),
(196, 12, 10, '2026-02-03', 'Component library is professional and reusable. Recharts integration done well - charts are responsive and informative. Dark/light theme implementation is smooth. Areas for improvement: 1) Add more variant states to buttons (disabled, loading), 2) Implement error boundaries for chart components, 3) Consider adding tooltips to chart data points for better UX.', '2026-02-07 06:35:40'),
(197, 12, 10, '2026-02-04', 'API integration work is solid. Redux Toolkit implementation follows best practices. Error handling is comprehensive. Good use of custom hooks. Suggestions: 1) Implement request cancellation with Axios cancel tokens, 2) Add request retry logic for failed API calls, 3) Consider using React Query instead of Redux for server state management in future projects.', '2026-02-07 06:35:40'),
(198, 12, 10, '2026-02-05', 'Performance optimization results are impressive! Lighthouse scores are excellent. Testing coverage at 85% is very good. Well done on identifying and fixing performance bottlenecks. Recommendations: 1) Add integration tests for critical user flows, 2) Implement virtualized lists for large datasets, 3) Consider using Web Workers for heavy computations.', '2026-02-07 06:35:40'),
(199, 12, 10, '2026-02-06', 'Deployment process is professional and well-documented. CI/CD pipeline setup shows good DevOps understanding. Cross-browser testing comprehensive. Documentation is thorough and will help other developers. Final suggestion: 1) Set up monitoring (error tracking with Sentry), 2) Add performance monitoring, 3) Create deployment checklist for future releases.', '2026-02-07 06:35:40');

-- --------------------------------------------------------

--
-- Table structure for table `internships`
--

CREATE TABLE `internships` (
  `id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `location` varchar(100) DEFAULT NULL,
  `duration` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `faculty` enum('FCI','FCM','FIST','FOM') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `internships`
--

INSERT INTO `internships` (`id`, `company_id`, `title`, `description`, `location`, `duration`, `created_at`, `faculty`) VALUES
(8, 9, 'Internship_Test', 'Testing internship applications', 'Malaysia', '6 months', '2026-01-21 14:43:17', 'FCI'),
(10, 13, 'Frontend Development Intern', 'Responsible for responsive web development and UI optimization', 'Kuala Lumpur', '3 months', '2026-02-07 02:26:41', 'FCI'),
(11, 13, 'Data Analysis Intern', 'Assist with data cleaning and visualization tasks', 'Penang', '6 months', '2026-02-07 02:27:42', 'FIST'),
(12, 13, 'Digital Marketing Intern', 'Participate in market research and digital campaign planning', 'Johor Bahru', '4 months', '2026-02-07 02:28:17', 'FOM'),
(13, 13, 'Medical Research Assistant', 'Assist with lab sample processing and data analysis', 'Selangor', '8 months', '2026-02-07 02:28:43', 'FCM');

-- --------------------------------------------------------

--
-- Table structure for table `logbook`
--

CREATE TABLE `logbook` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `internship_id` int(11) NOT NULL,
  `log_date` date NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `logbook`
--

INSERT INTO `logbook` (`id`, `student_id`, `internship_id`, `log_date`, `title`, `content`, `created_at`) VALUES
(123, 17, 13, '2025-06-09', 'Lab Safety Orientation', 'Completed lab safety training and certification. Learned about proper PPE usage, chemical handling, and emergency procedures at Liem Shiaw Wen laboratory.', '2026-02-07 05:49:03'),
(124, 17, 13, '2025-06-10', 'Laboratory Equipment Training', 'Trained on basic lab equipment: centrifuge, microscope, pipettes, and spectrophotometer. Practiced proper calibration and maintenance procedures.', '2026-02-07 05:49:03'),
(125, 17, 13, '2025-06-11', 'Sample Collection Protocols', 'Learned proper sample collection techniques for blood, tissue, and cell cultures. Studied aseptic techniques and contamination prevention.', '2026-02-07 05:49:03'),
(126, 17, 13, '2025-06-12', 'Sample Processing - Day 1', 'Processed first batch of blood samples: centrifugation, plasma separation, and proper storage at -20°C. Documented all procedures.', '2026-02-07 05:49:03'),
(127, 17, 13, '2025-06-13', 'Microscopy Techniques', 'Practiced using compound and fluorescence microscopes. Prepared slides and observed cell morphology in various samples.', '2026-02-07 05:49:03'),
(128, 17, 13, '2025-06-16', 'Cell Culture Basics', 'Learned cell culture techniques: media preparation, trypsinization, subculturing, and contamination monitoring.', '2026-02-07 05:49:03'),
(129, 17, 13, '2025-06-17', 'DNA Extraction Protocol', 'Performed DNA extraction from blood samples using spin column method. Measured DNA concentration with Nanodrop.', '2026-02-07 05:49:03'),
(130, 17, 13, '2025-06-18', 'PCR Setup and Optimization', 'Set up PCR reactions for gene amplification. Optimized annealing temperatures and cycle numbers.', '2026-02-07 05:49:03'),
(131, 17, 13, '2025-06-19', 'Gel Electrophoresis', 'Ran agarose gel electrophoresis to check PCR products. Learned gel preparation, loading, and imaging techniques.', '2026-02-07 05:49:03'),
(132, 17, 13, '2025-06-20', 'Data Recording System', 'Learned electronic lab notebook system. Entered all experimental data with proper metadata.', '2026-02-07 05:49:03'),
(133, 17, 13, '2025-06-23', 'Statistical Analysis Introduction', 'Started learning statistical analysis with SPSS. Basic descriptive statistics and t-tests.', '2026-02-07 05:49:03'),
(134, 17, 13, '2025-06-24', 'Protein Extraction', 'Performed protein extraction from tissue samples using RIPA buffer. Quantified with BCA assay.', '2026-02-07 05:49:03'),
(135, 17, 13, '2025-06-25', 'Western Blot - Part 1', 'Prepared samples for Western blot: SDS-PAGE gel casting and sample loading.', '2026-02-07 05:49:03'),
(136, 17, 13, '2025-06-26', 'Western Blot - Part 2', 'Completed Western blot: transfer to membrane, blocking, and antibody incubation.', '2026-02-07 05:49:03'),
(137, 17, 13, '2025-06-27', 'Western Blot Analysis', 'Imaged Western blot membranes and analyzed band intensities using ImageJ software.', '2026-02-07 05:49:03'),
(138, 17, 13, '2025-06-30', 'Research Project Assignment', 'Assigned to assist with ongoing cancer biomarker study. Reviewed literature and study protocol.', '2026-02-07 05:49:03'),
(139, 17, 13, '2025-07-01', 'ELISA Technique Training', 'Learned ELISA protocol for cytokine detection. Performed standard curve preparation.', '2026-02-07 05:49:03'),
(140, 17, 13, '2025-07-02', 'Sample Analysis - ELISA', 'Processed 50 patient samples for IL-6 levels using ELISA. All samples within detection range.', '2026-02-07 05:49:03'),
(141, 17, 13, '2025-07-03', 'Data Analysis - ELISA Results', 'Analyzed ELISA data with GraphPad Prism. Calculated concentrations and statistical significance.', '2026-02-07 05:49:03'),
(142, 17, 13, '2025-07-04', 'Cell Viability Assay', 'Performed MTT assay to test drug toxicity on cancer cell lines. 24-hour incubation started.', '2026-02-07 05:49:03'),
(143, 17, 13, '2025-07-07', 'MTT Assay Results', 'Measured absorbance for MTT assay. Calculated IC50 values for test compounds.', '2026-02-07 05:49:03'),
(144, 17, 13, '2025-07-08', 'Flow Cytometry Training', 'Basic training on flow cytometer operation. Learned sample preparation and gating strategies.', '2026-02-07 05:49:03'),
(145, 17, 13, '2025-07-09', 'Apoptosis Assay', 'Performed Annexin V/PI staining for apoptosis detection. Analyzed with flow cytometry.', '2026-02-07 05:49:03'),
(146, 17, 13, '2025-07-10', 'Clinical Data Collection', 'Collected clinical data from patient records for correlation with lab findings.', '2026-02-07 05:49:03'),
(147, 17, 13, '2025-07-11', 'Statistical Correlation Analysis', 'Correlated lab results with clinical parameters using Pearson correlation in SPSS.', '2026-02-07 05:49:03'),
(148, 17, 13, '2025-07-14', 'RNA Extraction', 'Extracted RNA from tissue samples using TRIzol method. Checked RNA quality with Bioanalyzer.', '2026-02-07 05:49:03'),
(149, 17, 13, '2025-07-15', 'cDNA Synthesis', 'Performed reverse transcription to convert RNA to cDNA. Optimized reaction conditions.', '2026-02-07 05:49:03'),
(150, 17, 13, '2025-07-16', 'qPCR Experiment', 'Set up quantitative PCR for gene expression analysis. Ran 96-well plate with triplicates.', '2026-02-07 05:49:03'),
(151, 17, 13, '2025-07-17', 'qPCR Data Analysis', 'Analyzed qPCR results using ΔΔCt method. Calculated fold changes in gene expression.', '2026-02-07 05:49:03'),
(152, 17, 13, '2025-07-18', 'Literature Review', 'Conducted literature review on recent biomarkers in oncology research.', '2026-02-07 05:49:03'),
(153, 17, 13, '2025-07-21', 'Lab Meeting Preparation', 'Prepared presentation for weekly lab meeting. Summarized two weeks of experimental results.', '2026-02-07 05:49:03'),
(154, 17, 13, '2025-07-22', 'Lab Meeting Presentation', 'Presented findings to research team. Received feedback on experimental design.', '2026-02-07 05:49:03'),
(155, 17, 13, '2025-07-23', 'Immunohistochemistry Training', 'Learned IHC staining protocol for tissue sections. Practiced antigen retrieval.', '2026-02-07 05:49:03'),
(156, 17, 13, '2025-07-24', 'Microscopy Analysis - IHC', 'Analyzed IHC-stained slides. Scored staining intensity for different markers.', '2026-02-07 05:49:03'),
(157, 17, 13, '2025-07-25', 'Data Organization', 'Organized all experimental data into structured database. Created backup files.', '2026-02-07 05:49:03'),
(158, 17, 13, '2025-07-28', 'Quality Control Procedures', 'Performed quality control checks on all lab equipment. Calibrated pipettes and balances.', '2026-02-07 05:49:03'),
(159, 17, 13, '2025-07-29', 'Research Ethics Training', 'Completed research ethics certification. Learned about patient confidentiality and data protection.', '2026-02-07 05:49:03'),
(160, 17, 13, '2025-07-30', 'Sample Inventory Management', 'Updated sample inventory database. Organized freezer storage system.', '2026-02-07 05:49:03'),
(161, 17, 13, '2025-07-31', 'Monthly Progress Review', 'Met with supervisor Dr. Liem. Reviewed progress and set goals for August.', '2026-02-07 05:49:03'),
(162, 17, 13, '2025-08-01', 'Independent Project Design', 'Started designing small independent project on drug resistance mechanisms.', '2026-02-07 05:49:03'),
(163, 17, 13, '2025-08-04', 'Cell Line Maintenance', 'Maintained three cancer cell lines: regular feeding and passaging.', '2026-02-07 05:49:03'),
(164, 17, 13, '2025-08-05', 'Drug Treatment Experiment', 'Treated cells with different drug concentrations for resistance study.', '2026-02-07 05:49:03'),
(165, 17, 13, '2025-08-06', 'Protein Analysis - Drug Treated', 'Harvested protein from treated cells for Western blot analysis of resistance markers.', '2026-02-07 05:49:03'),
(166, 17, 13, '2025-08-07', 'RNA Sequencing Prep', 'Prepared RNA samples for next-generation sequencing. Quality check passed.', '2026-02-07 05:49:03'),
(167, 17, 13, '2025-08-08', 'Data Mining - Public Databases', 'Mined gene expression data from TCGA and GEO databases for comparison.', '2026-02-07 05:49:03'),
(168, 17, 13, '2025-09-01', 'Bioinformatics Introduction', 'Started learning basic bioinformatics: sequence alignment and database searching.', '2026-02-07 05:49:03'),
(169, 17, 13, '2025-09-02', 'Statistical Software Advanced', 'Advanced SPSS training: ANOVA, regression analysis, and survival analysis.', '2026-02-07 05:49:03'),
(170, 17, 13, '2025-09-03', 'R Programming Basics', 'Started learning R programming for statistical analysis and data visualization.', '2026-02-07 05:49:03'),
(171, 17, 13, '2025-09-04', 'Data Visualization with R', 'Created publication-quality graphs using ggplot2 in R.', '2026-02-07 05:49:03'),
(172, 17, 13, '2025-09-05', 'Clinical Trial Data Analysis', 'Analyzed anonymized clinical trial data for treatment response correlations.', '2026-02-07 05:49:03'),
(173, 17, 13, '2025-10-01', 'Manuscript Draft - Methods', 'Started drafting methods section for potential publication.', '2026-02-07 05:49:03'),
(174, 17, 13, '2025-10-02', 'Results Section Writing', 'Wrote results section with tables and figures.', '2026-02-07 05:49:03'),
(175, 17, 13, '2025-10-03', 'Statistical Review', 'Reviewed all statistical analyses with biostatistician.', '2026-02-07 05:49:03'),
(176, 17, 13, '2025-10-06', 'Figure Preparation', 'Prepared publication-ready figures using Adobe Illustrator.', '2026-02-07 05:49:03'),
(177, 17, 13, '2025-10-07', 'Literature Citation', 'Completed literature review and citation management with EndNote.', '2026-02-07 05:49:03'),
(178, 17, 13, '2025-10-08', 'Abstract Writing', 'Drafted abstract for potential conference submission.', '2026-02-07 05:49:03'),
(179, 17, 13, '2025-10-09', 'Peer Review Practice', 'Practiced peer review by critiquing published papers.', '2026-02-07 05:49:03'),
(180, 17, 13, '2025-11-03', 'Mass Spectrometry Training', 'Basic training on LC-MS/MS for protein identification.', '2026-02-07 05:49:03'),
(181, 17, 13, '2025-11-04', 'Proteomics Sample Prep', 'Prepared samples for proteomic analysis.', '2026-02-07 05:49:03'),
(182, 17, 13, '2025-11-05', 'Metabolomics Introduction', 'Learned basics of metabolomic profiling techniques.', '2026-02-07 05:49:03'),
(183, 17, 13, '2025-11-06', 'CRISPR Technology Overview', 'Studied CRISPR-Cas9 applications in cancer research.', '2026-02-07 05:49:03'),
(184, 17, 13, '2025-11-07', 'Single-Cell Sequencing', 'Learned about single-cell RNA sequencing applications.', '2026-02-07 05:49:03'),
(185, 17, 13, '2025-12-01', 'Final Data Analysis', 'Completed all statistical analyses for research project.', '2026-02-07 05:49:03'),
(186, 17, 13, '2025-12-02', 'Research Report Writing', 'Writing comprehensive research report.', '2026-02-07 05:49:03'),
(187, 17, 13, '2025-12-03', 'Presentation Preparation', 'Preparing final presentation of research findings.', '2026-02-07 05:49:03'),
(188, 17, 13, '2025-12-04', 'Poster Design', 'Designing research poster for potential conference.', '2026-02-07 05:49:03'),
(189, 17, 13, '2025-12-05', 'Data Archiving', 'Archiving all research data according to lab protocols.', '2026-02-07 05:49:03'),
(190, 17, 13, '2026-01-05', 'Capstone Project Design', 'Designing final capstone project on personalized medicine.', '2026-02-07 05:49:03'),
(191, 17, 13, '2026-01-06', 'Patient-Derived Samples', 'Working with patient-derived xenograft samples.', '2026-02-07 05:49:03'),
(192, 17, 13, '2026-01-07', 'Drug Sensitivity Testing', 'Testing drug sensitivity on patient samples.', '2026-02-07 05:49:03'),
(193, 17, 13, '2026-01-08', 'Biomarker Validation', 'Validating potential biomarkers from earlier studies.', '2026-02-07 05:49:03'),
(194, 17, 13, '2026-01-09', 'Clinical Correlation', 'Correlating lab findings with clinical outcomes.', '2026-02-07 05:49:03'),
(195, 17, 13, '2026-02-02', 'Final Results Compilation', 'Compiling all final results and conclusions.', '2026-02-07 05:49:03'),
(196, 17, 13, '2026-02-03', 'Thesis Writing', 'Writing internship thesis document.', '2026-02-07 05:49:03'),
(197, 17, 13, '2026-02-04', 'Career Development Discussion', 'Meeting with supervisor about career paths in medical research.', '2026-02-07 05:49:03'),
(198, 17, 13, '2026-02-05', 'Lab Equipment Handover', 'Completing equipment maintenance and handover.', '2026-02-07 05:49:03'),
(199, 17, 13, '2026-02-06', 'Final Presentation Rehearsal', 'Rehearsing final presentation for department.', '2026-02-07 05:49:03'),
(200, 17, 13, '2026-02-07', 'Internship Completion', 'Final day: presentation, feedback session, and completion ceremony.', '2026-02-07 05:49:03'),
(201, 12, 10, '2026-02-02', 'Responsive Dashboard Development', 'Started developing admin dashboard with React and TypeScript. Created responsive grid layout using CSS Grid. Implemented sidebar navigation with collapsible menu items. Set up routing with React Router v6. Components created: Header, Sidebar, MainContent, Footer. Used Tailwind CSS for styling.', '2026-02-07 06:35:26'),
(202, 12, 10, '2026-02-03', 'Component Library & Data Visualization', 'Built reusable component library: Button (primary, secondary, danger variants), Card, Modal, Table, Form inputs. Integrated Recharts for data visualization: created LineChart for user growth, BarChart for revenue, PieChart for category distribution. Implemented dark/light theme toggle using Context API. Added loading skeletons for better UX.', '2026-02-07 06:35:26'),
(203, 12, 10, '2026-02-04', 'API Integration & State Management', 'Connected frontend to REST API using Axios. Implemented authentication flow: login, logout, token refresh. Set up Redux Toolkit for state management: created slices for user, products, orders. Added error handling and loading states. Created custom hooks: useFetch, useLocalStorage. Implemented form validation with Formik and Yup.', '2026-02-07 06:35:26'),
(204, 12, 10, '2026-02-05', 'Performance Optimization & Testing', 'Optimized application performance: implemented code splitting with React.lazy(), added React.memo() for expensive components, optimized images with WebP format. Set up Jest and React Testing Library: wrote 25 unit tests (85% coverage). Conducted Lighthouse audit: Performance 92, Accessibility 95, Best Practices 90, SEO 93. Fixed identified issues.', '2026-02-07 06:35:26'),
(205, 12, 10, '2026-02-06', 'Deployment & Documentation', 'Deployed application to Vercel: production build optimized. Set up CI/CD pipeline with GitHub Actions: automated testing and deployment. Created comprehensive documentation: README, component docs (Storybook), API integration guide. Conducted cross-browser testing (Chrome, Firefox, Safari, Edge). Mobile responsive testing completed on various screen sizes.', '2026-02-07 06:35:26');

-- --------------------------------------------------------

--
-- Table structure for table `logbook_feedback`
--

CREATE TABLE `logbook_feedback` (
  `id` int(11) NOT NULL,
  `log_id` int(11) NOT NULL,
  `supervisor_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `logbook_feedback`
--

INSERT INTO `logbook_feedback` (`id`, `log_id`, `supervisor_id`, `content`, `created_at`) VALUES
(143, 123, 10, 'Lab safety training completion excellent. PPE usage checklist: goggles, lab coat, gloves - all correct. Emergency shower/eyewash location memorized. Continue weekly safety refreshers.', '2026-02-07 06:32:05'),
(144, 124, 10, 'Equipment training comprehensive. Centrifuge balancing technique good. Pipette calibration: P20 error ±0.5%, P200 ±0.8%, P1000 ±1.2% - all within acceptable range (±2%).', '2026-02-07 06:32:05'),
(145, 125, 10, 'Aseptic technique proper. Blood collection: tourniquet time <1 min, needle gauge 21G appropriate. Tissue collection: sterile instruments, rapid processing (<30 mins) - good practice.', '2026-02-07 06:32:05'),
(146, 126, 10, 'First blood processing: 12 samples, centrifugation 1500×g ×15 min, plasma yield 2.5-3.0mL per sample. Storage: -20°C in labeled cryovials (PatientID_Date_Plasma).', '2026-02-07 06:32:05'),
(147, 127, 10, 'Microscopy: 10x, 40x, 100x objectives used correctly. Cell identification: RBCs, WBCs (neutrophils, lymphocytes) accurately distinguished. Include phase contrast for unstained samples.', '2026-02-07 06:32:05'),
(148, 128, 10, 'Cell culture: DMEM + 10% FBS, 5% CO2, 37°C conditions optimal. Seeding density: 1×10^5 cells/mL appropriate. Passage 3 cells - document doubling time (~24h).', '2026-02-07 06:32:05'),
(149, 129, 10, 'DNA extraction: Spin column method, elution volume 50µL TE buffer. Yield: 40-55µg, 260/280 ratio 1.8-1.9, 260/230 ratio 2.0-2.2 - excellent quality for PCR.', '2026-02-07 06:32:05'),
(150, 130, 10, 'PCR optimization: 25µL reaction, annealing temp gradient 55-65°C. Optimal: 58°C for β-actin (product 150bp). Cycle number: 35 cycles appropriate for detection.', '2026-02-07 06:32:05'),
(151, 131, 10, 'Gel electrophoresis: 2% agarose, 1× TAE buffer, 100V ×45 min. Ladder: 100bp (clear bands). Sample loading: 5µL PCR product + 1µL loading dye - proper volume.', '2026-02-07 06:32:05'),
(152, 132, 10, 'Electronic lab notebook: Experiment ID format EXP-YYYYMMDD-XXX established. Metadata: researcher, date, project, samples, protocol version - all complete.', '2026-02-07 06:32:05'),
(153, 133, 10, 'Statistics: Data from 3 independent experiments, n=5 per group. T-test: p<0.05 considered significant. Normal distribution checked with Shapiro-Wilk (p>0.05).', '2026-02-07 06:32:05'),
(154, 134, 10, 'Protein extraction: RIPA buffer + protease inhibitors, 30 min ice incubation. BCA assay: standard curve R²=0.998, sample concentrations 1.8-2.4 mg/mL - consistent.', '2026-02-07 06:32:05'),
(155, 135, 10, 'SDS-PAGE: 10% resolving gel, 5% stacking gel. Sample prep: 20µg protein + 5µL 4× sample buffer, boil 5 min at 95°C - denaturation complete.', '2026-02-07 06:32:05'),
(156, 136, 10, 'Western transfer: Wet transfer 100V ×60 min, PVDF membrane activated in methanol. Blocking: 5% BSA in TBST ×1 hr - reduces non-specific binding.', '2026-02-07 06:32:05'),
(157, 137, 10, 'ImageJ analysis: Lane profile tool used correctly. Background subtraction: rolling ball radius 50 pixels. Normalization: target/β-actin ratio calculated.', '2026-02-07 06:32:05'),
(158, 138, 10, 'Cancer biomarker project: Focus on CA-125, HE4 for ovarian cancer. Literature search: PubMed, keywords \"biomarker AND sensitivity AND specificity\" - relevant papers identified.', '2026-02-07 06:32:05'),
(159, 139, 10, 'ELISA: Sandwich ELISA for IL-6. Standard curve points: 0, 15.6, 31.2, 62.5, 125, 250, 500, 1000 pg/mL - covers dynamic range.', '2026-02-07 06:32:05'),
(160, 140, 10, 'ELISA processing: 50 patient samples + 10 controls. Incubation: capture antibody overnight, sample 2h, detection antibody 1h, substrate 30 min - timing optimal.', '2026-02-07 06:32:05'),
(161, 141, 10, 'GraphPad analysis: 4-parameter logistic curve fit. Sample concentrations: 45-850 pg/mL (normal range <7 pg/mL). Elevated IL-6 in 22/50 samples - significant finding.', '2026-02-07 06:32:05'),
(162, 142, 10, 'MTT assay: Cell seeding 5×10^3/well, drug treatment 24h, MTT incubation 4h, DMSO solubilization. Drug concentrations: 0.1, 1, 10, 50, 100 µM - appropriate range.', '2026-02-07 06:32:05'),
(163, 143, 10, 'IC50 results: Compound A: 8.3 µM (95% CI: 6.7-10.2), Compound B: 25.1 µM (95% CI: 20.3-31.0). Compound A more potent - clinically relevant concentration.', '2026-02-07 06:32:05'),
(164, 144, 10, 'Flow cytometry: BD FACSCanto II, 488nm laser. Compensation: single-color controls for FITC, PE, PerCP-Cy5.5. Gating: FSC vs SSC to exclude debris, then fluorescence.', '2026-02-07 06:32:05'),
(165, 145, 10, 'Apoptosis: Annexin V-FITC/PI staining. Results: Control 5% apoptotic, Treated 45% apoptotic. Early apoptotic (Annexin V+/PI-): 28%, Late (Annexin V+/PI+): 17%.', '2026-02-07 06:32:05'),
(166, 146, 10, 'Clinical data: 50 patients, age 45-72, mean 58.3±8.7. Variables: age, sex, stage, treatment, response. Anonymized IDs: OV-001 to OV-050 - proper coding.', '2026-02-07 06:32:05'),
(167, 147, 10, 'Correlation: IL-6 levels vs tumor stage. Stage I/II: 65±28 pg/mL, Stage III/IV: 320±145 pg/mL. Pearson r=0.72, p<0.001 - strong positive correlation.', '2026-02-07 06:32:05'),
(168, 148, 10, 'RNA extraction: TRIzol method, isopropanol precipitation. Quality: RIN 8.2-8.7 (Bioanalyzer), 260/280 2.0-2.1, concentration 300-500 ng/µL - excellent for qPCR.', '2026-02-07 06:32:05'),
(169, 149, 10, 'cDNA synthesis: 1µg RNA, random hexamers, M-MLV RT enzyme. Reaction: 25°C×10min, 37°C×50min, 70°C×15min. cDNA dilution: 1:10 for qPCR - optimal template amount.', '2026-02-07 06:32:05'),
(170, 150, 10, 'qPCR: SYBR Green chemistry, 96-well plate. Cycling: 95°C×10min, then 40 cycles of 95°C×15s, 60°C×60s. Triplicate Ct values: SD<0.3 - technical precision good.', '2026-02-07 06:32:05'),
(171, 151, 10, 'ΔΔCt: Reference genes: GAPDH (Ct ~18), β-actin (Ct ~19). Target gene: BCL2 (Ct ~24). Fold change: Treatment/Control = 0.45±0.08 (55% downregulation).', '2026-02-07 06:32:05'),
(172, 152, 10, 'Literature review: 25 papers analyzed. Key findings: 1) IL-6 promotes resistance, 2) BCL2 overexpression in 60% cases, 3) STAT3 pathway activation - good synthesis.', '2026-02-07 06:32:05'),
(173, 153, 10, 'Lab meeting prep: 12 slides total. Structure: 1) Background, 2) Methods, 3) Results, 4) Discussion, 5) Future work - logical flow.', '2026-02-07 06:32:05'),
(174, 154, 10, 'Presentation: 15 minutes exactly. Q&A: 6 questions answered correctly. Feedback: include more statistical details (n values, error bars) in slides.', '2026-02-07 06:32:05'),
(175, 155, 10, 'IHC training: Paraffin sections (4µm), deparaffinization: xylene 2×5min, ethanol gradient. Antigen retrieval: citrate buffer pH6.0, pressure cooker 15 min.', '2026-02-07 06:32:05'),
(176, 156, 10, 'IHC scoring: 50 tumor sections evaluated. Staining: 0=negative, 1+=weak, 2+=moderate, 3+=strong. H-score calculation: sum of (intensity × percentage).', '2026-02-07 06:32:05'),
(177, 157, 10, 'Data organization: Folder structure: /RawData, /ProcessedData, /Figures, /Manuscript. File naming: Project_Experiment_Date_Researcher_vX.', '2026-02-07 06:32:05'),
(178, 158, 10, 'QC: Pipettes (6) calibrated: all within ±2%. Centrifuge: speed verified with tachometer. Microscope: objectives cleaned, Köhler illumination set.', '2026-02-07 06:32:05'),
(179, 159, 10, 'Ethics: IRB approval #IRB-2025-0456. Consent forms: 50/50 collected and scanned. Data encryption: AES-256 for electronic files.', '2026-02-07 06:32:05'),
(180, 160, 10, 'Inventory: Freezer -80°C: Box A1-A6 (DNA), B1-B6 (RNA), C1-C6 (Protein). Log: sample ID, location, date, amount, freeze-thaw cycles (max 2).', '2026-02-07 06:32:05'),
(181, 161, 10, 'Monthly review: July achievements: 1) ELISA optimization, 2) qPCR validation, 3) IHC scoring. August goals: 1) RNA-seq prep, 2) R programming, 3) Manuscript draft.', '2026-02-07 06:32:05'),
(182, 162, 10, 'Project design: Title \"Cisplatin Resistance Mechanisms in Ovarian Cancer\". Hypothesis: ABC transporters and DNA repair genes upregulated. Aims: 3 specific objectives.', '2026-02-07 06:32:05'),
(183, 163, 10, 'Cell maintenance: 3 lines: A2780 (sensitive), A2780-CisR (resistant), SKOV3. Passage: every 3-4 days at 80-90% confluency. Mycoplasma test: negative.', '2026-02-07 06:32:05'),
(184, 164, 10, 'Drug treatment: Cisplatin concentrations: 0, 1, 5, 10, 20, 50 µM ×48h. Controls: vehicle (PBS), positive control (paclitaxel 10nM). Timing: start Monday 9am, harvest Wednesday 9am.', '2026-02-07 06:32:05'),
(185, 165, 10, 'Protein harvest: After 48h treatment, wash with PBS, lyse with RIPA. Protein concentration: A2780 2.1mg/mL, A2780-CisR 1.9mg/mL, SKOV3 2.3mg/mL - consistent.', '2026-02-07 06:32:05'),
(186, 166, 10, 'RNA-seq prep: PolyA selection, library prep with NEBNext Ultra II. QC: Bioanalyzer - library size 300bp, concentration 15nM - passes QC for sequencing.', '2026-02-07 06:32:05'),
(187, 167, 10, 'Data mining: TCGA database - OV dataset (n=316). GEO: GSE26712, GSE51088. Analysis: differential expression (DESeq2), survival analysis (Kaplan-Meier).', '2026-02-07 06:32:05'),
(188, 168, 10, 'Bioinformatics: Sequence alignment with BLAST (E-value<0.01). Multiple alignment: Clustal Omega. Phylogenetic tree: MEGA software - basics understood.', '2026-02-07 06:32:05'),
(189, 169, 10, 'Statistics advanced: One-way ANOVA for 3+ groups. Post-hoc: Tukey HSD test. Assumptions: normality (Shapiro-Wilk), homogeneity of variance (Levene test).', '2026-02-07 06:32:05'),
(190, 170, 10, 'R programming: Basic syntax learned. Packages: ggplot2, dplyr, DESeq2. Script: data import, filtering, visualization - functional code written.', '2026-02-07 06:32:05'),
(191, 171, 10, 'Data visualization: ggplot2: scatter plots, box plots, heatmaps. Colors: viridis palette for accessibility. Figure dimensions: 8.5×11 cm for publication.', '2026-02-07 06:32:05'),
(192, 172, 10, 'Clinical trial data: Phase II trial data (n=120). Variables: PFS (progression-free survival), OS (overall survival). Analysis: Cox proportional hazards model.', '2026-02-07 06:32:05'),
(193, 173, 10, 'Manuscript methods: Detailed protocol descriptions. Reagents: catalog numbers (Sigma-Aldrich C3956), concentrations, incubation times - reproducible.', '2026-02-07 06:32:05'),
(194, 174, 10, 'Results writing: Tables: Table 1 patient characteristics, Table 2 biomarker levels. Figures: 6 figures planned (Western, ELISA, qPCR, IHC, survival, model).', '2026-02-07 06:32:05'),
(195, 175, 10, 'Statistical review: All p-values reported, confidence intervals included. Multiple testing correction: Benjamini-Hochberg FDR<0.05. Sample sizes justified with power analysis.', '2026-02-07 06:32:05'),
(196, 176, 10, 'Figure prep: Adobe Illustrator: panels labeled A-F, scale bars added, font Arial 8pt. Resolution: 300 dpi for print, 72 dpi for web. Color mode: CMYK for print.', '2026-02-07 06:32:05'),
(197, 177, 10, 'Citation management: EndNote library: 87 references. Style: Vancouver for medical journals. Reference check: all DOIs verified, PubMed IDs included.', '2026-02-07 06:32:05'),
(198, 178, 10, 'Abstract writing: 250 words, structure: Background, Methods, Results, Conclusion. Keywords: ovarian cancer, biomarkers, resistance, IL-6, BCL2 - appropriate.', '2026-02-07 06:32:05'),
(199, 179, 10, 'Peer review: Critiqued 2 papers. Comments: statistical methods unclear in Paper1, Figure resolution low in Paper2 - constructive feedback provided.', '2026-02-07 06:32:05'),
(200, 180, 10, 'Mass spectrometry: LC-MS/MS: Q Exactive HF. Sample: trypsin-digested peptides. Analysis: MaxQuant software, database: UniProt human proteome - basics learned.', '2026-02-07 06:32:05'),
(201, 181, 10, 'Proteomics prep: Protein reduction: DTT 10mM, alkylation: iodoacetamide 55mM. Digestion: trypsin 1:50 w/w, 37°C overnight. Desalting: C18 StageTips - protocol followed.', '2026-02-07 06:32:05'),
(202, 182, 10, 'Metabolomics: GC-MS for small molecules. Sample derivatization: MSTFA. Analysis: MetaboAnalyst software. Applications: biomarker discovery, pathway analysis.', '2026-02-07 06:32:05'),
(203, 183, 10, 'CRISPR: sgRNA design: 20bp target + PAM (NGG). Delivery: lentivirus. Controls: non-targeting sgRNA. Applications: gene knockout, activation (CRISPRa), inhibition (CRISPRi).', '2026-02-07 06:32:05'),
(204, 184, 10, 'Single-cell seq: 10x Genomics platform. Applications: tumor heterogeneity, immune cell profiling. Data: UMAP visualization, cluster annotation - concepts understood.', '2026-02-07 06:32:05'),
(205, 185, 10, 'Final analysis: All data compiled: n=50 patients, 3 cell lines, 8 experiments. Statistical tests: t-tests, ANOVA, correlation, survival analysis - comprehensive.', '2026-02-07 06:32:05'),
(206, 186, 10, 'Report writing: 45 pages total. Sections: Introduction (5p), Methods (10p), Results (15p), Discussion (10p), References (5p) - balanced structure.', '2026-02-07 06:32:05'),
(207, 187, 10, 'Presentation prep: 20 slides for 15-minute talk. Timing: 45 seconds per slide. Practice: 3 rehearsals completed, timing within ±30 seconds.', '2026-02-07 06:32:05'),
(208, 188, 10, 'Poster design: Size: 36×48 inches (portrait). Sections: Title, Introduction, Methods, Results, Discussion, References. QR code linking to full data included.', '2026-02-07 06:32:05'),
(209, 189, 10, 'Data archiving: Raw data: 25GB. Storage: lab server + cloud backup. Metadata: README file with experiment details. Format: .fastq, .txt, .xlsx, .jpg - organized.', '2026-02-07 06:32:05'),
(210, 190, 10, 'Capstone project: Title \"Personalized Medicine Approaches in Ovarian Cancer\". Focus: PDX models, drug screening, genomic profiling - translational research design.', '2026-02-07 06:32:05'),
(211, 191, 10, 'PDX samples: 5 patient-derived xenograft models. Processing: tumor fragmentation, cryopreservation in 90% FBS + 10% DMSO. Storage: liquid nitrogen vapor phase.', '2026-02-07 06:32:05'),
(212, 192, 10, 'Drug screening: 12 drugs tested on PDX cells. Platform: 384-well plates. Readout: CellTiter-Glo luminescence. Z-factor >0.5 indicates robust assay.', '2026-02-07 06:32:05'),
(213, 193, 10, 'Biomarker validation: Candidates from RNA-seq: ABCC1, ERCC1, XPA. Validation: qPCR (fold changes 2.5-4.0×), Western blot (protein level increase) - consistent results.', '2026-02-07 06:32:05'),
(214, 194, 10, 'Clinical correlation: Biomarker levels vs treatment response. Responders: low ABCC1 expression (p=0.003). Non-responders: high ERCC1 (p=0.008) - clinically significant.', '2026-02-07 06:32:05'),
(215, 195, 10, 'Final compilation: All results summarized in 10 tables, 15 figures. Conclusions: 1) IL-6 elevated in resistant cases, 2) BCL2 downregulated by treatment, 3) ABCC1 predicts response.', '2026-02-07 06:32:05'),
(216, 196, 10, 'Thesis writing: 80 pages submitted. Format: university template. Chapters: 1-5 complete. References: 120 citations. Appendices: raw data, protocols, ethics approval.', '2026-02-07 06:32:05'),
(217, 197, 10, 'Career discussion: Options: 1) PhD (cancer biology), 2) Research assistant (academic lab), 3) Clinical research associate (pharma). Recommendation: apply to 3 PhD programs.', '2026-02-07 06:32:05'),
(218, 198, 10, 'Equipment handover: All equipment cleaned, calibrated, documented. Maintenance logs updated. Training provided to new intern - smooth transition ensured.', '2026-02-07 06:32:05'),
(219, 199, 10, 'Presentation rehearsal: Timing: 14 min 45 sec (within limit). Slides: 18 total. Q&A practice: 10 anticipated questions prepared with answers.', '2026-02-07 06:32:05'),
(220, 200, 10, 'Internship completion: Duration: 8 months (June 2025-Feb 2026). Achievements: 1) Mastered 15+ lab techniques, 2) Contributed to 2 research projects, 3) Drafted 1 manuscript. Recommendation: Excellent performance, ready for advanced research career. Maintain lab connections for collaborations and references.', '2026-02-07 06:32:05'),
(221, 201, 10, 'Dashboard development progress excellent. CSS Grid implementation is responsive. React Router v6 setup correct. Suggestions: 1) Add aria-labels for sidebar navigation items for screen readers, 2) Implement mobile hamburger menu for screens <768px, 3) Use CSS custom properties for consistent spacing variables.', '2026-02-07 06:35:52'),
(222, 202, 10, 'Component library design system well-architected. Button variants cover most use cases. Recharts integration good - consider adding responsive container wrapper. Theme toggle implementation clean using Context API. Loading skeletons improve perceived performance.', '2026-02-07 06:35:52'),
(223, 203, 10, 'API integration with Axios done properly. Authentication flow secure (token storage in httpOnly cookies recommended for production). Redux Toolkit slices organized well. Formik + Yup validation comprehensive. Custom hooks show good abstraction skills.', '2026-02-07 06:35:52'),
(224, 204, 10, 'Performance optimization techniques correctly applied. React.lazy() for route-based code splitting. React.memo() usage appropriate for expensive components. Lighthouse scores impressive. Test coverage at 85% meets industry standards. Consider adding E2E tests with Cypress.', '2026-02-07 06:35:52'),
(225, 205, 10, 'Deployment to Vercel successful. GitHub Actions CI/CD pipeline automated. Documentation comprehensive: README includes setup, development, deployment instructions. Cross-browser testing shows attention to detail. Storybook for component documentation is professional.', '2026-02-07 06:35:52');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `message` text NOT NULL,
  `target_role` enum('all','student','company','academic') NOT NULL DEFAULT 'all',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `title`, `message`, `target_role`, `created_at`) VALUES
(6, 'System Maintenance Scheduled', 'The internship system will undergo maintenance on February 15, 2026 from 2:00 AM to 6:00 AM UTC. The system will be temporarily unavailable during this period. Please save your work and log out before the maintenance window.', 'all', '2026-02-07 01:00:00'),
(7, 'Database Backup Completed', 'Weekly database backup completed successfully on February 7, 2026 at 3:00 AM. All user data, internship records, and logs have been securely backed up.', 'all', '2026-02-06 19:30:00'),
(8, 'Internship Application Deadline Reminder', 'Final reminder: Spring 2026 internship applications close on February 28, 2026 at 11:59 PM. Students who have not yet applied are encouraged to submit their applications before the deadline.', 'student', '2026-02-07 02:00:00'),
(9, 'Logbook Submission Reminder', 'Weekly logbook entries are due every Friday by 5:00 PM. Students currently on internship should ensure their weekly activities are documented in the system.', 'student', '2026-02-07 06:00:00'),
(10, 'Academic Supervisor Assignment', 'All FCI students have been assigned academic supervisors. Please check your profile to see your assigned supervisor and schedule your first meeting.', 'student', '2026-02-06 03:00:00'),
(11, 'New Internship Posting Guidelines', 'Companies posting new internships: Please ensure all required fields are completed including duration, location, faculty requirements, and detailed job description. Incomplete postings may be rejected.', 'company', '2026-02-07 01:30:00'),
(12, 'Student Evaluation Due', 'Reminder: Student evaluations for completed internships are due by March 15, 2026. Please complete the evaluation forms for students who finished their internships in January.', 'company', '2026-02-07 07:00:00'),
(13, 'Company Profile Verification', 'All company profiles must be verified by March 1, 2026. Please ensure your company information, contact details, and documentation are up to date.', 'company', '2026-02-06 08:00:00'),
(14, 'Student Logbook Review Required', 'You have 15 pending student logbook entries requiring your feedback. Please review and provide feedback by February 10, 2026.', 'academic', '2026-02-07 03:00:00'),
(15, 'Mid-Internship Evaluation Period', 'Mid-internship evaluations for students are due by February 20, 2026. Please schedule meetings with your assigned students and submit evaluations through the system.', 'academic', '2026-02-06 06:30:00'),
(16, 'Academic Supervisor Meeting', 'Monthly academic supervisor meeting scheduled for February 15, 2026 at 2:00 PM in Conference Room A. Agenda: Student progress review and curriculum alignment.', 'academic', '2026-02-05 02:00:00'),
(17, 'New Feature Release: Mobile App', 'We are excited to announce the release of our new mobile app! Download now from App Store and Google Play. Features: logbook entry, attendance tracking, and notifications on the go.', 'all', '2026-02-05 01:00:00'),
(18, 'Security Update Required', 'Important security update: All users must change their passwords by February 14, 2026. Use strong passwords with minimum 12 characters including uppercase, lowercase, numbers, and special characters.', 'all', '2026-02-04 05:00:00'),
(19, 'Internship Fair 2026', 'Annual Internship Fair 2026 will be held on March 10-12, 2026. Companies: Register to participate. Students: Prepare your resumes and portfolios.', 'all', '2026-02-03 02:00:00'),
(20, 'System Performance Issue Resolved', 'The system performance issue reported earlier today has been resolved. All services are now running normally. We apologize for any inconvenience caused.', 'all', '2026-02-07 08:00:00'),
(21, 'Phishing Attempt Warning', 'Security Alert: Be aware of phishing emails pretending to be from our internship system. Official emails will only come from @internship-system.edu. Do not click suspicious links.', 'all', '2026-02-02 06:00:00'),
(22, 'Semester Break Reminder', 'Semester break begins March 15, 2026. Students on internship during break: Ensure your attendance is properly recorded. Companies: Please accommodate student schedules.', 'all', '2026-02-01 03:00:00'),
(23, 'Final Exam Period', 'Final exams scheduled for May 10-25, 2026. Students: Balance internship commitments with exam preparation. Consider flexible schedules with your supervisors.', 'student', '2026-01-30 02:00:00'),
(24, 'Frontend Development Workshop', 'Free workshop: \"Advanced React Patterns\" on February 20, 2026 at 3:00 PM in Lab 304. Open to all FCI students. Registration required - limited seats available.', 'student', '2026-02-07 05:00:00'),
(25, 'Company Supervisor Training', 'Mandatory training for new company supervisors: \"Effective Intern Mentoring\" on February 25, 2026 at 10:00 AM via Zoom. Registration link sent to company emails.', 'company', '2026-02-06 07:00:00'),
(26, 'New Report Generation Feature', 'New feature released: Custom report generation. Companies and academic supervisors can now generate custom reports for student performance, attendance, and logbook completion.', 'all', '2026-02-05 08:00:00'),
(27, 'Mobile App Update v2.1', 'Mobile app update v2.1 released with bug fixes and performance improvements. New feature: Push notifications for important updates. Please update your app from the store.', 'all', '2026-02-04 09:00:00'),
(28, 'System Feedback Survey', 'Help us improve! Please complete our user satisfaction survey by February 15, 2026. Your feedback is valuable for enhancing the internship system. Survey link in your dashboard.', 'all', '2026-02-03 04:00:00'),
(29, 'Internship Experience Survey', 'Students who completed internships in January 2026: Please complete the internship experience survey. Your feedback helps improve the program for future students.', 'student', '2026-02-02 01:00:00'),
(30, 'Student Achievement Recognition', 'Congratulations to Student_Test (ID: 8) for outstanding performance during internship at Company_Test! Recognition certificate awarded. View success stories in the achievements section.', 'all', '2026-02-01 07:00:00'),
(31, 'Top Company Partner Award', 'Congratulations to Company_Test for being awarded \"Top Internship Provider 2025\"! Thank you for your commitment to student development and quality internship programs.', 'all', '2026-01-31 03:00:00'),
(32, 'Attendance Policy Update', 'Updated attendance policy: Students must maintain minimum 80% attendance during internship. Medical leaves require proper documentation. New policy effective March 1, 2026.', 'all', '2026-01-30 06:00:00'),
(33, 'Data Privacy Policy Update', 'Data privacy policy updated in compliance with new regulations. Key changes: Enhanced data protection measures, clearer consent forms. Review updated policy in the documentation section.', 'all', '2026-01-29 02:00:00'),
(34, 'Chinese New Year Holiday', 'The internship system office will be closed for Chinese New Year from January 28-30, 2026. Emergency support available via email. Wishing everyone a prosperous new year!', 'all', '2026-01-27 08:00:00'),
(35, 'System Anniversary Celebration', 'Celebrating 5 years of the Internship Management System! Thank you to all students, companies, and academic staff for making this platform successful. Special events planned throughout February.', 'all', '2026-02-01 01:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `admin_setup_password_hash` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `admin_setup_password_hash`) VALUES
(1, '$2y$10$iuqzRVEuzrL6QStnHlELV.4oNu62algxgrhVx2Axdo2oq6KlFpORy');

-- --------------------------------------------------------

--
-- Table structure for table `student_internships`
--

CREATE TABLE `student_internships` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `internship_id` int(11) NOT NULL,
  `status` enum('applied','approved','rejected','in_progress','completed') DEFAULT 'applied',
  `accepted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `evaluation_score` int(11) DEFAULT NULL,
  `evaluation_comment` text DEFAULT NULL,
  `academic_score` int(11) DEFAULT NULL,
  `academic_comment` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_internships`
--

INSERT INTO `student_internships` (`id`, `student_id`, `internship_id`, `status`, `accepted_at`, `evaluation_score`, `evaluation_comment`, `academic_score`, `academic_comment`) VALUES
(21, 12, 10, 'in_progress', '2026-02-07 02:56:11', NULL, NULL, NULL, NULL),
(22, 16, 11, 'applied', '2026-02-07 02:57:22', NULL, NULL, NULL, NULL),
(24, 17, 13, 'completed', '2026-02-07 06:16:30', 92, 'Exceptional performance throughout 8-month medical research internship at Liem Shiaw Wen laboratory. Technical Skills: Mastered DNA/RNA extraction, PCR, qPCR, Western blot, ELISA, cell culture, flow cytometry, and immunohistochemistry. Showed excellent precision in sample processing and data analysis. Research Contribution: Assisted in cancer biomarker study, processed over 200 patient samples, conducted statistical analysis using SPSS and R. Independent project on drug resistance mechanisms showed innovation. Laboratory Practices: Maintained perfect safety record, meticulous documentation, equipment calibration, and sample inventory management. Soft Skills: Effective communication in lab meetings, collaborative teamwork, problem-solving abilities. Areas of Excellence: Statistical analysis, technical precision, scientific writing, research ethics understanding. Overall outstanding performance suitable for research career.', 95, 'Academic Excellence: Successfully integrated FCM curriculum knowledge with practical laboratory applications. Research Methodology: Demonstrated thorough understanding of experimental design, controls, replication, and validation. Statistical Analysis: Proficient in descriptive statistics, t-tests, ANOVA, correlation analysis, and data visualization using GraphPad Prism and R. Scientific Communication: Drafted manuscript sections, prepared conference-quality poster, delivered professional presentations. Critical Thinking: Showed ability to analyze research literature, design experiments, interpret complex data. Ethics and Professionalism: Completed research ethics certification, maintained patient confidentiality, followed ethical guidelines. Learning Outcomes: All internship learning objectives exceeded. Demonstrated potential for postgraduate research. Recommendation: Strong candidate for honors thesis or research assistant position. Overall academic performance: Excellent.');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('student','company','academic','admin') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `academic_supervisor_id` int(11) DEFAULT NULL,
  `faculty` enum('FCI','FCM','FIST','FOM') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`, `academic_supervisor_id`, `faculty`) VALUES
(7, 'System Admin', 'admin@example.com', '$2y$10$imlrJf77NPPn8VM5yC1y7eTU7gOHByH662ot2F0KkeQ9azoB9s8hi', 'admin', '2026-01-21 11:28:07', NULL, NULL),
(8, 'Student_Test', 'student@example.com', '$2y$10$6L9IWjWFIXT0DqXE1p3vz.gIXKu9NpLmTVklxPnIAmyWtkYLTvVYC', 'student', '2026-01-21 14:40:09', 10, 'FCM'),
(9, 'Company_Test', 'company@example.com', '$2y$10$ec7Lr0MGfeHkhwVP3RMBw.qmVUbxMV9vHE76jsooBBbTDnA3CbEri', 'company', '2026-01-21 14:41:11', NULL, NULL),
(10, 'Academic_Test', 'academic@example.com', '$2y$10$MLLJBIbpVkHUaSLF89fTJuTSuOcJ2IkxuTfmVD0adygQJHhztK4PK', 'academic', '2026-01-21 14:41:30', NULL, NULL),
(12, 'Yap Yee Xuen', 'yap.yee.xuen@student.com', '$2y$10$O8CvGlJQFNP3ShhXvlliUOxy425RgZ3Cf/fneTBrcOJz3J6MGKAFq', 'student', '2026-02-05 14:13:24', 15, 'FOM'),
(13, 'Liem Shiaw Wen', 'liem.shiaw.wen@company.com', '$2y$10$eeCCOi/nF9ufBxdGTyXcrO7wHqdJxW5o4Mn8qeDpQBy3qpZeS.U7G', 'company', '2026-02-05 14:15:14', NULL, 'FCI'),
(14, 'Melvin Hee Yi Heng', '1211108115@admin.com', '$2y$10$Yi0dcsecjhsS8qYB/jMqbOlIKKPB2hVzUToAEGaSIWCPVLlV6v8ie', 'admin', '2026-02-05 15:19:31', NULL, NULL),
(15, 'Dr.Tan Khai Yu', 'tan.khai.yu@academic.com', '$2y$10$6TX8PZZ7V57NiLt1s47SN.jxr3vaWwg1kPIewoNpYljoNuzBmbGoi', 'academic', '2026-02-05 15:25:39', NULL, 'FCI'),
(16, 'Alex', 'alex@student.com', '$2y$10$i5.Eq601bmuiI3F/Siofhu0XLnghkvwkVwvwcfuf5DMklasRj7oiC', 'student', '2026-02-07 02:57:04', NULL, NULL),
(17, 'Ben', 'ben@student.com', '$2y$10$gVrwqTOOjeLRsUD02UCJPO1zvx.5HbJ/sVY7O9iHt/uVJmiu.kG6K', 'student', '2026-02-07 02:57:55', 10, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `academic_requests`
--
ALTER TABLE `academic_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_id` (`student_id`,`internship_id`,`date`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_id` (`student_id`,`internship_id`,`feedback_date`);

--
-- Indexes for table `internships`
--
ALTER TABLE `internships`
  ADD PRIMARY KEY (`id`),
  ADD KEY `company_id` (`company_id`);

--
-- Indexes for table `logbook`
--
ALTER TABLE `logbook`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `logbook_feedback`
--
ALTER TABLE `logbook_feedback`
  ADD PRIMARY KEY (`id`),
  ADD KEY `log_id` (`log_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_internships`
--
ALTER TABLE `student_internships`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `internship_id` (`internship_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `academic_requests`
--
ALTER TABLE `academic_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=200;

--
-- AUTO_INCREMENT for table `internships`
--
ALTER TABLE `internships`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `logbook`
--
ALTER TABLE `logbook`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=206;

--
-- AUTO_INCREMENT for table `logbook_feedback`
--
ALTER TABLE `logbook_feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=226;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `student_internships`
--
ALTER TABLE `student_internships`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `internships`
--
ALTER TABLE `internships`
  ADD CONSTRAINT `internships_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `logbook_feedback`
--
ALTER TABLE `logbook_feedback`
  ADD CONSTRAINT `logbook_feedback_ibfk_1` FOREIGN KEY (`log_id`) REFERENCES `logbook` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `student_internships`
--
ALTER TABLE `student_internships`
  ADD CONSTRAINT `student_internships_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `student_internships_ibfk_2` FOREIGN KEY (`internship_id`) REFERENCES `internships` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

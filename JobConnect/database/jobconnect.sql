-- JobConnect Database Structure

CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('student','recruiter','admin') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE student_profiles (
    student_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    phone VARCHAR(15),
    college VARCHAR(100),
    course VARCHAR(100),
    skills TEXT,
    resume VARCHAR(255),
    FOREIGN KEY (user_id) REFERENCES users(user_id)
    ON DELETE CASCADE
);


CREATE TABLE recruiter_profiles (
    recruiter_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    company_name VARCHAR(150),
    company_website VARCHAR(255),
    company_location VARCHAR(100),
    FOREIGN KEY (user_id) REFERENCES users(user_id)
    ON DELETE CASCADE
);


CREATE TABLE jobs (
    job_id INT AUTO_INCREMENT PRIMARY KEY,
    recruiter_id INT NOT NULL,
    job_title VARCHAR(150) NOT NULL,
    company_name VARCHAR(150),
    location VARCHAR(100),
    salary VARCHAR(50),
    job_type VARCHAR(50),
    description TEXT,
    posted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (recruiter_id) REFERENCES recruiter_profiles(recruiter_id)
    ON DELETE CASCADE
);


CREATE TABLE applications (
    application_id INT AUTO_INCREMENT PRIMARY KEY,
    job_id INT NOT NULL,
    student_id INT NOT NULL,
    status ENUM('Applied','Shortlisted','Rejected','Selected')
    DEFAULT 'Applied',
    applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (job_id) REFERENCES jobs(job_id)
    ON DELETE CASCADE,

    FOREIGN KEY (student_id) REFERENCES student_profiles(student_id)
    ON DELETE CASCADE
);


-- Default Admin Account
-- Password is already encrypted using password_hash()

INSERT INTO users
(full_name,email,password,role)
VALUES
(
'Administrator',
'admin@jobconnect.com',
'$2y$10$Ti4eLqgSK1KXkB.5QY2Ybe3D90Lf4m7yQF8wXoS9b3Zl1b0x8P9K2',
'admin'
);
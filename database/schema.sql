
CREATE DATABASE IF NOT EXISTS hourpay;

USE hourpay;

CREATE TABLE jobs (
    job_id INT AUTO_INCREMENT PRIMARY KEY,
    arbeitgeber VARCHAR(100) NOT NULL,
    bezeichnung VARCHAR(100) NOT NULL,
    stundenlohn DECIMAL(6,2) NOT NULL
);


CREATE TABLE arbeitseinsaetze (
    einsatz_id INT AUTO_INCREMENT PRIMARY KEY,
    job_id INT NOT NULL,
    datum DATE NOT NULL,
    startzeit TIME NOT NULL,
    endzeit TIME NOT NULL,
    pause INT NOT NULL DEFAULT 0,
    stundenlohn DECIMAL(6,2) NOT NULL,

    FOREIGN KEY (job_id) REFERENCES jobs(job_id)
        ON DELETE RESTRICT
);

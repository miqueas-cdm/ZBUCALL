-- Portal do Colaborador - Database Schema
-- MySQL Database Schema

-- Drop existing tables if they exist
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS notifications;
DROP TABLE IF EXISTS requests;
DROP TABLE IF EXISTS communications;
DROP TABLE IF EXISTS documents;
DROP TABLE IF EXISTS time_records;
DROP TABLE IF EXISTS payslips;
DROP TABLE IF EXISTS benefits;
DROP TABLE IF EXISTS employees;
SET FOREIGN_KEY_CHECKS = 1;

-- Employees table
CREATE TABLE employees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    registration_number VARCHAR(20) UNIQUE NOT NULL,
    full_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    role ENUM('employee', 'admin') DEFAULT 'employee',
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    mobile VARCHAR(20),
    birth_date DATE,
    hire_date DATE NOT NULL,
    position VARCHAR(100) NOT NULL,
    department VARCHAR(100) NOT NULL,
    address_street VARCHAR(255),
    address_number VARCHAR(20),
    address_complement VARCHAR(100),
    address_neighborhood VARCHAR(100),
    address_city VARCHAR(100),
    address_state VARCHAR(2),
    address_zipcode VARCHAR(10),
    photo_url VARCHAR(255) DEFAULT 'assets/images/default-avatar.png',
    status ENUM('active', 'inactive', 'vacation', 'leave') DEFAULT 'active',
    cpf VARCHAR(14) UNIQUE,
    otp_code VARCHAR(6),
    otp_expires_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Benefits table
CREATE TABLE benefits (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id INT NOT NULL,
    benefit_type VARCHAR(50) NOT NULL,
    benefit_name VARCHAR(100) NOT NULL,
    description TEXT,
    value DECIMAL(10,2) DEFAULT 0,
    balance DECIMAL(10,2) DEFAULT 0,
    status ENUM('active', 'inactive', 'pending') DEFAULT 'active',
    category VARCHAR(50),
    dependents INT DEFAULT 0,
    grace_period_end DATE,
    additional_info JSON,
    start_date DATE,
    end_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE,
    INDEX idx_employee_benefit (employee_id, benefit_type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Payslips table
CREATE TABLE payslips (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id INT NOT NULL,
    reference_month INT NOT NULL,
    reference_year INT NOT NULL,
    gross_salary DECIMAL(10,2) NOT NULL,
    net_salary DECIMAL(10,2) NOT NULL,
    deductions DECIMAL(10,2) DEFAULT 0,
    benefits_total DECIMAL(10,2) DEFAULT 0,
    inss DECIMAL(10,2) DEFAULT 0,
    irrf DECIMAL(10,2) DEFAULT 0,
    fgts DECIMAL(10,2) DEFAULT 0,
    worked_hours DECIMAL(8,2) DEFAULT 0,
    overtime_hours DECIMAL(8,2) DEFAULT 0,
    pdf_file VARCHAR(255),
    details JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE,
    UNIQUE KEY unique_payslip (employee_id, reference_month, reference_year),
    INDEX idx_employee_date (employee_id, reference_year, reference_month)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Time Records table
CREATE TABLE time_records (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id INT NOT NULL,
    record_date DATE NOT NULL,
    clock_in_1 TIME,
    clock_out_1 TIME,
    clock_in_2 TIME,
    clock_out_2 TIME,
    total_hours DECIMAL(5,2) DEFAULT 0,
    overtime_hours DECIMAL(5,2) DEFAULT 0,
    status ENUM('normal', 'late', 'absent', 'holiday', 'leave') DEFAULT 'normal',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE,
    UNIQUE KEY unique_record (employee_id, record_date),
    INDEX idx_employee_date (employee_id, record_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Documents table
CREATE TABLE documents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id INT NOT NULL,
    document_type VARCHAR(50) NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    file_name VARCHAR(255) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    file_size INT,
    mime_type VARCHAR(100),
    category ENUM('policy', 'manual', 'certificate', 'card', 'other') DEFAULT 'other',
    is_public BOOLEAN DEFAULT FALSE,
    uploaded_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE,
    INDEX idx_employee_type (employee_id, document_type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Communications table
CREATE TABLE communications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    category ENUM('news', 'notice', 'campaign', 'classified', 'announcement') DEFAULT 'news',
    priority ENUM('low', 'normal', 'high', 'urgent') DEFAULT 'normal',
    image_url VARCHAR(255),
    author_id INT,
    published_at TIMESTAMP NULL,
    expires_at TIMESTAMP NULL,
    status ENUM('draft', 'published', 'archived') DEFAULT 'published',
    views INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (author_id) REFERENCES employees(id) ON DELETE SET NULL,
    INDEX idx_status_published (status, published_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Requests table
CREATE TABLE requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id INT NOT NULL,
    request_type ENUM('vacation', 'hr', 'cadastral', 'declaration', 'other') NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    start_date DATE,
    end_date DATE,
    status ENUM('pending', 'approved', 'rejected', 'cancelled') DEFAULT 'pending',
    priority ENUM('low', 'normal', 'high') DEFAULT 'normal',
    attachments JSON,
    reviewer_id INT,
    reviewed_at TIMESTAMP NULL,
    review_notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE,
    FOREIGN KEY (reviewer_id) REFERENCES employees(id) ON DELETE SET NULL,
    INDEX idx_employee_status (employee_id, status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Notifications table
CREATE TABLE notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    type ENUM('info', 'success', 'warning', 'error') DEFAULT 'info',
    icon VARCHAR(50),
    link_url VARCHAR(255),
    is_read BOOLEAN DEFAULT FALSE,
    read_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE,
    INDEX idx_employee_read (employee_id, is_read, created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample employee data
-- Password: 'senha123' (in production, use proper hashing like password_hash())
INSERT INTO employees (
    registration_number, full_name, email, role, password,
    phone, mobile, birth_date, hire_date,
    position, department,
    address_street, address_number, address_neighborhood,
    address_city, address_state, address_zipcode,
    status, cpf
) VALUES 
(
    'EMP001', 'João Silva Santos', 'joao.silva@empresa.com', 'employee',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    '(11) 3456-7890', '(11) 98765-4321', '1990-05-15', '2020-01-10',
    'Desenvolvedor Full Stack', 'Tecnologia da Informação',
    'Avenida Paulista', '1000', 'Bela Vista',
    'São Paulo', 'SP', '01310-100',
    'active', '123.456.789-00'
),
(
    'EMP002', 'Maria Oliveira Costa', 'maria.oliveira@empresa.com', 'admin',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    '(11) 3456-7891', '(11) 98765-4322', '1988-08-20', '2019-03-15',
    'Gerente de RH', 'Recursos Humanos',
    'Rua Augusta', '500', 'Consolação',
    'São Paulo', 'SP', '01305-000',
    'active', '987.654.321-00'
);

-- Insert sample benefits
INSERT INTO benefits (employee_id, benefit_type, benefit_name, description, value, balance, status, category, dependents) VALUES
(1, 'transport', 'Vale-Transporte', 'Benefício para transporte diário', 250.00, 150.00, 'active', 'Recarga', 0),
(1, 'meal', 'Vale-Alimentação', 'Cartão alimentação mensal', 500.00, 420.00, 'active', 'Recarga', 0),
(1, 'food', 'Vale-Refeição', 'Cartão refeição mensal', 600.00, 350.00, 'active', 'Recarga', 0),
(1, 'health', 'Plano de Saúde', 'Plano de saúde empresarial', 450.00, 0, 'active', 'Enfermaria', 2),
(1, 'dental', 'Plano Odontológico', 'Plano odontológico empresarial', 80.00, 0, 'active', 'Básico', 2),
(1, 'life', 'Seguro de Vida', 'Seguro de vida em grupo', 50.00, 0, 'active', 'Cobertura básica', 0),
(2, 'transport', 'Vale-Transporte', 'Benefício para transporte diário', 250.00, 200.00, 'active', 'Recarga', 0),
(2, 'meal', 'Vale-Alimentação', 'Cartão alimentação mensal', 500.00, 480.00, 'active', 'Recarga', 0),
(2, 'food', 'Vale-Refeição', 'Cartão refeição mensal', 600.00, 550.00, 'active', 'Recarga', 0),
(2, 'health', 'Plano de Saúde', 'Plano de saúde empresarial', 450.00, 0, 'active', 'Apartamento', 1);

-- Insert sample payslips
INSERT INTO payslips (employee_id, reference_month, reference_year, gross_salary, net_salary, deductions, benefits_total, inss, irrf, fgts, worked_hours, overtime_hours) VALUES
(1, 10, 2025, 8000.00, 6450.00, 1550.00, 1930.00, 880.00, 450.00, 640.00, 176.00, 8.00),
(1, 9, 2025, 8000.00, 6520.00, 1480.00, 1930.00, 880.00, 380.00, 640.00, 176.00, 0.00),
(1, 8, 2025, 8000.00, 6420.00, 1580.00, 1930.00, 880.00, 480.00, 640.00, 168.00, 12.00),
(2, 10, 2025, 12000.00, 9200.00, 2800.00, 1350.00, 1320.00, 1200.00, 960.00, 176.00, 0.00),
(2, 9, 2025, 12000.00, 9180.00, 2820.00, 1350.00, 1320.00, 1220.00, 960.00, 176.00, 0.00);

-- Insert sample time records (last 30 days)
INSERT INTO time_records (employee_id, record_date, clock_in_1, clock_out_1, clock_in_2, clock_out_2, total_hours, overtime_hours, status) VALUES
(1, '2025-11-01', '08:00:00', '12:00:00', '13:00:00', '18:00:00', 9.00, 0.00, 'normal'),
(1, '2025-11-04', '08:00:00', '12:00:00', '13:00:00', '18:00:00', 9.00, 0.00, 'normal'),
(1, '2025-11-05', '08:00:00', '12:00:00', '13:00:00', '18:00:00', 9.00, 0.00, 'normal'),
(1, '2025-11-06', '08:00:00', '12:00:00', '13:00:00', '19:00:00', 10.00, 1.00, 'normal'),
(1, '2025-11-07', '08:00:00', '12:00:00', '13:00:00', '18:00:00', 9.00, 0.00, 'normal'),
(1, '2025-11-08', '08:00:00', '12:00:00', '13:00:00', '18:00:00', 9.00, 0.00, 'normal'),
(1, '2025-11-11', '09:00:00', '12:00:00', '13:00:00', '18:00:00', 8.00, 0.00, 'late'),
(1, '2025-11-12', '08:00:00', '12:00:00', '13:00:00', '18:00:00', 9.00, 0.00, 'normal'),
(2, '2025-11-01', '09:00:00', '12:00:00', '13:00:00', '18:00:00', 8.00, 0.00, 'normal'),
(2, '2025-11-04', '09:00:00', '12:00:00', '13:00:00', '18:00:00', 8.00, 0.00, 'normal'),
(2, '2025-11-05', '09:00:00', '12:00:00', '13:00:00', '18:00:00', 8.00, 0.00, 'normal');

-- Insert sample communications
INSERT INTO communications (title, content, category, priority, author_id, published_at, status) VALUES
('Bem-vindo ao Portal do Colaborador', 'Estamos felizes em apresentar o novo Portal do Colaborador! Aqui você encontrará todas as informações importantes sobre benefícios, holerites, ponto eletrônico e muito mais.', 'announcement', 'high', 2, NOW(), 'published'),
('Campanha de Vacinação 2025', 'A empresa está promovendo uma campanha de vacinação contra gripe. As doses estarão disponíveis no ambulatório médico de 15 a 30 de novembro.', 'campaign', 'normal', 2, NOW(), 'published'),
('Novos Horários de Refeitório', 'Informamos que a partir de 1º de dezembro, o refeitório terá novos horários de funcionamento: Almoço: 11:30 às 14:30', 'notice', 'normal', 2, NOW(), 'published'),
('Processo Seletivo Interno', 'Estamos com vagas abertas para Analista Sênior de TI. Colaboradores interessados podem se candidatar através do portal até 10/12/2025.', 'news', 'normal', 2, NOW(), 'published');

-- Insert sample requests
INSERT INTO requests (employee_id, request_type, title, description, start_date, end_date, status, priority) VALUES
(1, 'vacation', 'Solicitação de Férias - Janeiro 2026', 'Gostaria de solicitar férias no período de 05/01/2026 a 19/01/2026 (15 dias)', '2026-01-05', '2026-01-19', 'pending', 'normal'),
(1, 'declaration', 'Declaração de Vínculo Empregatício', 'Solicito declaração de vínculo empregatício para apresentação ao banco.', NULL, NULL, 'approved', 'normal'),
(2, 'cadastral', 'Atualização de Endereço', 'Mudei de endereço recentemente e preciso atualizar meus dados cadastrais.', NULL, NULL, 'pending', 'normal');

-- Insert sample notifications
INSERT INTO notifications (employee_id, title, message, type, icon, is_read) VALUES
(1, 'Holerite Disponível', 'Seu holerite de outubro/2025 já está disponível para consulta.', 'info', 'file-text', FALSE),
(1, 'Férias Aprovadas', 'Sua solicitação de férias foi aprovada! Período: 05/01/2026 a 19/01/2026', 'success', 'check-circle', FALSE),
(1, 'Novo Comunicado', 'Uma nova notícia foi publicada: Campanha de Vacinação 2025', 'info', 'bell', TRUE),
(2, 'Holerite Disponível', 'Seu holerite de outubro/2025 já está disponível para consulta.', 'info', 'file-text', FALSE),
(2, 'Bem-vindo!', 'Seja bem-vindo ao Portal do Colaborador!', 'success', 'smile', TRUE);

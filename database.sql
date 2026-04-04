-- =============================================
-- Website Viewer Database
-- Import file này vào phpMyAdmin
-- =============================================

CREATE DATABASE IF NOT EXISTS `website_viewer` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `website_viewer`;

DROP TABLE IF EXISTS `websites`;
CREATE TABLE `websites` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `category` VARCHAR(255) NOT NULL,
    `demo_url` VARCHAR(500) NOT NULL,
    `password` VARCHAR(100) DEFAULT '',
    `official_url` VARCHAR(500) DEFAULT '',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `websites` (`category`, `demo_url`, `password`, `official_url`) VALUES
('XE MÁY', 'https://mau01.hydrosite.site/', '765426', 'https://xemay365.com.vn/'),
('KIM TUYẾN', 'https://mau02.hydrosite.site/', '876542', 'https://dodo-glitter.com/'),
('ĐÈN TRẦN XUYÊN SÁNG', 'https://mau03.hydrosite.site/', '476654', 'https://dentranxuyensang.com/'),
('MỸ PHẨM (NEW)', 'https://mau05.hydrosite.site/', '888542', 'https://scentlabo.vn/'),
('NỒI HƠI - MÁY MÓC (NEW)', 'https://mau06.hydrosite.site/', '091098', 'https://tounshingkai.com.vn/vi/'),
('MÁY LỌC KHÔNG KHÍ', 'https://mau07.hydrosite.site/', '091098', ''),
('NHÀ XE - ĐẶT VÉ XE KHÁCH', 'https://mau08.hydrosite.site/', '091098', 'xekhachhanoiyty.com'),
('VẬN CHUYỂN HLE EXPRESS', 'https://mau09.hydrosite.site/', '091098', 'hleexpress.com'),
('THAN ĐÁ', 'https://mau10.hydrosite.site/', '091098', 'https://thanhuynhphuong.vn/'),
('NỘI THẤT', 'https://mau11.hydrosite.site/', '091098', ''),
('NỘI THẤT', 'https://mau12.hydrosite.site/', '091098', 'https://j-design.info/'),
('VISA - TOUR DU LỊCH', 'https://mau14.hydrosite.site/', '091098', 'https://saigonfirstsky.com/'),
('RESORT (NEW)', 'https://mau15.hydrosite.site/', '091098', 'https://cloudparadiseyty.com/'),
('VISA VIỆT NHẬT (NEW)', 'https://mau16.hydrosite.site/', '981009', 'https://favijatravel.com/'),
('THIẾT KẾ XÂY DỰNG (NEW)', 'https://mau17.hydrosite.site/', '091098', 'https://thietkexaydungbinhminh.com/'),
('NỒI HƠI - MÁY MÓC (NEW)', 'https://mau18.hydrosite.site/', '091098', 'https://petpboiler.com/'),
('NỘI THẤT', 'https://mau19.hydrosite.site/', '091098', 'https://bdstan.com/'),
('BÁNH TRUNG THU (NEW)', 'https://mau20.hydrosite.site/', '865453', ''),
('LOGISTIC - VẬN CHUYỂN HÀN QUỐC (NEW)', 'https://mau21.hydrosite.site/', '846213', 'https://jinakorea.co.kr/'),
('LỐP XE - RESTONE (NEW)', 'https://mau22.hydrosite.site/', '654322', 'https://restone-tire.com/'),
('KEM HAPPY COOL (NEW)', 'https://mau23.hydrosite.site/', '756856', ''),
('THUỶ SẢN - VS SEAFOOD (NEW)', 'https://mau24.hydrosite.site/', '157896', 'https://vietseavn.com/en/'),
('TECHNO FARM - NÔNG TRẠI (NEW)', 'https://mau25.hydrosite.site/', '765156', ''),
('GAABOR - SHOPEE MALL (NEW)', 'https://mau26.hydrosite.site/', '975156', 'https://gaaborvn.com/'),
('SPA MASSAGE', 'https://mau27.hydrosite.site/', '563489', ''),
('HELLO HOKKAIDO - KIMONO - CHỤP ẢNH (NEW)', 'https://mau28.hydrosite.site/', '682043', 'https://hellohokkaido.com'),
('Siêu Metis (DaLat Milk)', 'https://mau29.hydrosite.site/', '', ''),
('DD OFFROAD (NEW)', 'https://mau30.hydrosite.site/', '956431', ''),
('RƯỢU ĐỒ MƯỜNG ĐÌNH (LANDING PAGE)', 'https://mau31.hydrosite.site/', '586456', 'https://ruoudomuongdinh.vn/'),
('SPA - SKIN TREATMENT BY TRANG (NEW)', 'https://mau32.hydrosite.site/', '846546', ''),
('PHỤ TÙNG KHÁNH CHÂU (NEW)', 'https://mau33.hydrosite.site/', '898651', 'https://ptkhanhchau.com/'),
('LUX MASSAGE (NEW)', 'https://mau34.hydrosite.site/', '896435', ''),
('BAMBI - Ghế Ô tô trẻ em 3D (NEW)', 'https://mau35.hydrosite.site/', '896456', ''),
('AVA COFFEE', 'https://mau36.hydrosite.site/', '894563', ''),
('GALAXY PROPERTY (NEW)', 'https://mau37.hydrosite.site/', '768964', ''),
('SHOPEE', 'https://mau38.hydrosite.site/', '071196', 'shopee'),
('Aura Realty (Web BDS)', 'https://web01.hydrosite.vn/', '', ''),
('Kim Long Hoa (Web Nội Thất)', 'https://web02.hydrosite.vn/', '', ''),
('Phụ tùng xe Đại Thắng Phát', 'https://web03.hydrosite.vn/', '', '');

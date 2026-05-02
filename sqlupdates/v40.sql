ALTER TABLE `addresses`
MODIFY `user_id` BIGINT UNSIGNED NULL,
ADD COLUMN `temp_user_id` BIGINT UNSIGNED NULL AFTER `user_id`;


INSERT INTO `permissions` (`id`, `name`, `parent`, `guard_name`, `created_at`, `updated_at`) VALUES (NULL, 'approve_offline_order_payment', 'offline_payment', 'web', '2025-09-22 05:11:29', '2025-09-22 05:11:29');

INSERT INTO `translations` (`id`, `lang`, `lang_key`, `lang_value`, `created_at`, `updated_at`) VALUES
(null, 'en', 'please_select_the_varient', 'Please Select the Product Varient\r\n', '2025-09-08 07:41:09', '2025-09-08 07:41:09'),
(null, 'en', 'select_option', 'Select Option\r\n', '2025-09-21 07:41:09', '2025-09-21 07:41:09'),
(null, 'en', 'verification_code', 'Verification Code\r\n', '2025-09-21 07:41:09', '2025-09-21 07:41:09'),
(null, 'en', 'enter_your_verification_code', 'Enter your Verification Code\r\n', '2025-09-21 07:41:09', '2025-09-21 07:41:09'),
(null, 'en', 'home_delivery', 'Home Delivery\r\n', '2025-09-08 07:41:11', '2025-09-11 07:41:09');


INSERT INTO `settings` (`id`, `type`, `value`, `created_at`, `updated_at`, `deleted_at`) VALUES
(null, 'express_delivery_option', '1', '2025-09-08 01:36:58', '2025-09-08 01:36:58', NULL),
(null, 'google_recaptcha', '0', '2025-09-28 01:36:58', '2025-09-28 01:36:58', NULL);


UPDATE `settings` SET `value` = '4.0' WHERE `type` = 'current_version';
COMMIT;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Структура таблицы `lk_logs`
--

CREATE TABLE `lk_logs` (
  `log_id` int(11) NOT NULL,
  `log_name` text NOT NULL,
  `log_date` text NOT NULL,
  `log_content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `lk_pays`
--

CREATE TABLE `lk_pays` (
  `pay_id` int(11) NOT NULL,
  `pay_order` int(11) NOT NULL,
  `pay_auth` text NOT NULL,
  `pay_summ` float NOT NULL,
  `pay_data` text NOT NULL,
  `pay_system` text NOT NULL,
  `pay_promo` text NOT NULL,
  `pay_status` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `lk_pay_service`
--

CREATE TABLE `lk_pay_service` (
  `id` int(11) NOT NULL,
  `name_kassa` text NOT NULL,
  `shop_id` text NOT NULL,
  `secret_key_1` text NOT NULL,
  `secret_key_2` text NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `lk_promocodes`
--

CREATE TABLE `lk_promocodes` (
  `id` int(11) NOT NULL,
  `code` text NOT NULL,
  `percent` float NOT NULL,
  `attempts` int(11) NOT NULL,
  `auth1` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `lk_logs`
--
ALTER TABLE `lk_logs`
  ADD PRIMARY KEY (`log_id`);

--
-- Индексы таблицы `lk_pays`
--
ALTER TABLE `lk_pays`
  ADD PRIMARY KEY (`pay_id`);

--
-- Индексы таблицы `lk_promocodes`
--
ALTER TABLE `lk_promocodes`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `lk_logs`
--
ALTER TABLE `lk_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;
--
-- AUTO_INCREMENT для таблицы `lk_pays`
--
ALTER TABLE `lk_pays`
  MODIFY `pay_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;
--
-- AUTO_INCREMENT для таблицы `lk_promocodes`
--
ALTER TABLE `lk_promocodes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;
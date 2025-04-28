type DateInput = string | Date;

/**
 * Массив с русскими месяцами.
 */
const months = [
  'Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь',
  'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'
];

/**
 * Массив с русскими днями недели.
 */
const weekdays = [
  'Воскресенье', 'Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота'
];

/**
 * Функция для форматирования даты в русском формате.
 * @param date Дата, которую нужно отформатировать.
 * @param format Формат, в котором нужно отобразить дату (по умолчанию 'DD MMMM YYYY, HH:mm').
 * @returns Отформатированная строка.
 */
export const formatDate = (date: DateInput, format: string = 'DD MMMM YYYY, HH:mm'): string => {
  const d = new Date(date);

  const day = d.getDate().toString().padStart(2, '0');
  const month = months[d.getMonth()];
  const year = d.getFullYear();
  const hours = d.getHours().toString().padStart(2, '0');
  const minutes = d.getMinutes().toString().padStart(2, '0');
  const weekday = weekdays[d.getDay()];

  // Заменяем форматы в строке
  return format
    .replace('DD', day)
    .replace('MMMM', month)
    .replace('YYYY', year.toString())
    .replace('HH', hours)
    .replace('mm', minutes)
    .replace('dddd', weekday);
};

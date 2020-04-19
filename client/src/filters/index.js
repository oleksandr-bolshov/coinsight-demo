import {parseISO, format} from 'date-fns';

const formatMarketValue = value => {
  if (value === null || isNaN(value)) {
    return;
  }

  return '$' + value.toLocaleString('en-US');
};

const prettifyDate = date => {
  return format(parseISO(date), 'MMMM dd, yyyy');
};

const formatPercent = percent => {
  return (percent > 0 ? '+' + percent : percent) + '%';
};

export {formatMarketValue, prettifyDate, formatPercent};

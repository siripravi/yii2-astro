import { Component } from 'react';

export default class StarRating extends Component {
  constructor(props) {
    super(props);
    this.state = {
      rating: props.initial || 0
    };
  }

  componentDidMount() {
    const input = document.getElementById(this.props.fieldId);
    if (input && input.value) {
      this.setState({ rating: parseInt(input.value) || 0 });
    }
  }

  handleClick = (value) => {
    this.setState({ rating: value });
    
    const input = document.getElementById(this.props.fieldId);
    if (input) {
      input.value = value;
      const event = new Event('input', { bubbles: true });
      input.dispatchEvent(event);
    }
  };

  render() {
    const { max = 5 } = this.props;
    const { rating } = this.state;

    return (
      <div style={{ 
        display: 'flex',
        gap: '0.5rem',
        fontSize: '1.5rem'
      }}>
        {Array.from({ length: max }, (_, i) => {
          const value = i + 1;
          return (
            <span
              key={value}
              onClick={() => this.handleClick(value)}
              style={{
                color: value <= rating ? 'gold' : 'gray',
                cursor: 'pointer',
                userSelect: 'none'
              }}
            >
              ★
            </span>
          );
        })}
      </div>
    );
  }
}
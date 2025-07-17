import { j as jsxRuntimeExports } from './jsx-runtime.DqGXlEPz.js';
import { r as reactExports } from './index.BfTCejMq.js';

class StarRating extends reactExports.Component {
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
      const event = new Event("input", { bubbles: true });
      input.dispatchEvent(event);
    }
  };
  render() {
    const { max = 5 } = this.props;
    const { rating } = this.state;
    return /* @__PURE__ */ jsxRuntimeExports.jsx("div", { style: {
      display: "flex",
      gap: "0.5rem",
      fontSize: "1.5rem"
    }, children: Array.from({ length: max }, (_, i) => {
      const value = i + 1;
      return /* @__PURE__ */ jsxRuntimeExports.jsx(
        "span",
        {
          onClick: () => this.handleClick(value),
          style: {
            color: value <= rating ? "gold" : "gray",
            cursor: "pointer",
            userSelect: "none"
          },
          children: "★"
        },
        value
      );
    }) });
  }
}

export { StarRating as default };

import React, { Component } from 'react';

class ParamItem extends Component {
  constructor(props) {
    super(props);

    this.state = {
      name: props.model.name,
      description: props.model.description,
    };
  }

  onNameChange = e => {
    this.setState({ name: e.target.value });

    setTimeout(() => {
      this.update();
    });
  };

  onDescriptionChange = e => {
    this.setState({ description: e.target.value });

    setTimeout(() => {
      this.update();
    });
  };

  update = () => this.props.onUpdate ? this.props.onUpdate({
    name: this.state.name,
    description: this.state.description
  }) : null;

  render() {
    const { name, description } = this.state;
    const { canDelete, isFirst, isLast } = this.props;

    return (
        <tr>
          <td><input type="text" className="form-control" value={name} onChange={this.onNameChange} /></td>
          <td><textarea className="form-control" value={description} onChange={this.onDescriptionChange} /></td>
          <td className="param-cell__actions">
            {isFirst ? null :
                <button className="param-action param-action--up" onClick={this.onUpClick.bind(this)}>Вверх</button>}
            {isLast ? null :
                <button className="param-action param-action--down" onClick={this.onDownClick.bind(this)}>Вниз</button>}
            {canDelete ? <button className="param-action param-action--delete" onClick={this.onDeleteClick.bind(this)}>
              Удалить</button> : null}
          </td>
        </tr>
    );
  }

  onDeleteClick() {
    if (this.props.onDelete) {
      this.props.onDelete();
    }
  }

  onUpClick() {
    if (this.props.onDelete) {
      this.props.onDelete();
    }
  }

  onDownClick() {
    if (this.props.onMove) {
      this.props.onMove(1);
    }
  }

  onUpClick() {
    if (this.props.onMove) {
      this.props.onMove(-1);
    }
  }
}

export default ParamItem;
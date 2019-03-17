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
    const { canDelete } = this.props;

    return (
        <tr>
          <td><input type="text" className="form-control" value={name} onChange={this.onNameChange} /></td>
          <td><textarea className="form-control" value={description} onChange={this.onDescriptionChange} /></td>
          <td className="param-cell__delete">
            {canDelete ?
                <button className="param-delete" onClick={this.onDeleteClick.bind(this)}>Удалить</button> : null}
          </td>
        </tr>
    );
  }

  onDeleteClick() {
    if (this.props.onDelete) {
      this.props.onDelete();
    }
  }
}

export default ParamItem;
import React, { Component } from 'react';
import { connect } from 'react-redux';
import styled from 'styled-components';

import { loadPrices } from '../ducks/params';

import SectionHeader from "../components/Section/SectionHeader";
import PricesContainerSingle from "../components/PricesContainer/PricesContainerSingle";
import PricesContainerDouble from "../components/PricesContainer/PricesContainerDouble";
import BlockWithLoader from "../components/ui/BlockWithLoader";

import ParamsService from '../services/params';

const paramsService = new ParamsService;

class PricesSection extends Component {
  componentWillMount() {
    this.state = {
      changedPrice: false,
      loading: false,
    };

    this.props.loadPrices(this.props.productId);
  }

  onSaveClick(e) {
    e.preventDefault();

    this.setState({
      loading: true
    });

    this.setState({
      changedPrice: true
    });

    paramsService.saveBatch(this.props.productId, Object.values(this.props.prices)).then(() => {
      this.setState({
        loading: false,
        changedPrice: false
      })
    }).catch(error => {
      alert('Ошибка сохранения цен');

      this.setState({
        loading: false,
      });
    });
  }

  render() {
    const { params } = this.props;

    if (params.length === 0) {
      return null;
    }

    return (
      <div className="section">
        <SectionHeader title="Цены" />
        <div className="section-content">
          <BlockWithLoader loading={this.state.loading}>
            {this.renderBody()}
          </BlockWithLoader>
        </div>
        {this.state.changedPrice ? (
          <ButtonRow>
            {this.state.loading ? <LoadingText>Сохранение...</LoadingText> :
              <button onClick={e => this.onSaveClick(e)} className="btn btn-success">Сохранить</button>}
          </ButtonRow>
        ) : null}
      </div>
    );
  }

  onChange() {
    this.setState({
      changedPrice: true
    });

  }

  renderBody() {
    const { params, paramItems } = this.props;

    if (params.length === 1) {
      return <PricesContainerSingle model={params[0]}
                                    items={params[0].id in paramItems ? paramItems[params[0].id].filter(item => item.serverId !== null) : []}
                                    onChange={data => this.onChange(data)}
      />
    } else if (params.length === 2) {
      return <PricesContainerDouble firstModel={params[0]}
                                    firstItems={params[0].id in paramItems ? paramItems[params[0].id].filter(item => item.serverId !== null) : []}
                                    secondModel={params[1]}
                                    secondItems={params[1].id in paramItems ? paramItems[params[1].id].filter(item => item.serverId !== null) : []}
                                    onChange={data => this.onChange(data)}
      />
    } else {
      return null;
    }
  }
}

const ButtonRow = styled.div`
  position: fixed;
  bottom: 20px;
  right: 20px;
  z-index: 22;
`;

const LoadingText = styled.span`
  display: block;
  padding: 5px 15px;
  background: #fff;
  border: 1px solid #eee;
`;

function mapStateToProps(state) {
  return {
    productId: state.common.productId,
    params: state.params.params,
    prices: state.params.prices,
    paramItems: state.params.paramItems
  }
}

export default connect(mapStateToProps, { loadPrices })(PricesSection);
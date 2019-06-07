import React, { Component } from 'react';
import { connect } from 'react-redux';

import { loadPrices } from '../ducks/params';

import SectionHeader from "../components/Section/SectionHeader";
import PricesContainerSingle from "../components/PricesContainer/PricesContainerSingle";
import PricesContainerDouble from "../components/PricesContainer/PricesContainerDouble";

class PricesSection extends Component {
  componentWillMount() {
    this.props.loadPrices(this.props.productId);
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
            {this.renderBody()}
          </div>
        </div>
    );
  }

  renderBody() {
    const { params, paramItems } = this.props;

    if (params.length === 1) {
      return <PricesContainerSingle model={params[0]}
                                    items={params[0].id in paramItems ? paramItems[params[0].id].filter(item => item.serverId !== null) : []} />
    } else if (params.length === 2) {
      return <PricesContainerDouble firstModel={params[0]}
                                    firstItems={params[0].id in paramItems ? paramItems[params[0].id].filter(item => item.serverId !== null): []}
                                    secondModel={params[1]}
                                    secondItems={params[1].id in paramItems ? paramItems[params[1].id].filter(item => item.serverId !== null) : []}
      />
    } else {
      return null;
    }
  }
}

function mapStateToProps(state) {
  return {
    productId: state.common.productId,
    params: state.params.params,
    paramItems: state.params.paramItems
  }
}

export default connect(mapStateToProps, { loadPrices })(PricesSection);
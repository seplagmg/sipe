CREATE TABLE IF NOT EXISTS `rl_documentos_pastas` (
  `es_pasta` int(10) unsigned NOT NULL,
  `es_documento` int(10) unsigned NOT NULL,
  `es_instituicao` int(10) unsigned NOT NULL,
  `dt_cadastro` datetime NOT NULL,
  `es_usuario` int(10) unsigned NOT NULL,
  PRIMARY KEY (`es_pasta`,`es_documento`),
  KEY `es_pasta` (`es_pasta`),
  KEY `es_documento` (`es_documento`),
  KEY `es_usuario` (`es_usuario`),
  KEY `es_instituicao` (`es_instituicao`),
  CONSTRAINT `rl_documentos_pastas_ibfk_2` FOREIGN KEY (`es_pasta`) REFERENCES `tb_pastas` (`pr_pasta`),
  CONSTRAINT `rl_documentos_pastas_ibfk_3` FOREIGN KEY (`es_usuario`) REFERENCES `tb_usuarios` (`pr_usuario`),
  CONSTRAINT `rl_documentos_pastas_ibfk_4` FOREIGN KEY (`es_documento`) REFERENCES `tb_documentos` (`pr_documento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE IF NOT EXISTS `rl_instituicoes_pastas` (
  `es_instituicao` int(10) unsigned NOT NULL,
  `es_pasta` int(10) unsigned NOT NULL,
  `dt_cadastro` date DEFAULT NULL,
  `es_cadastrador` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`es_instituicao`,`es_pasta`),
  KEY `es_cadastrador` (`es_cadastrador`),
  KEY `es_pasta` (`es_pasta`),
  CONSTRAINT `rl_instituicoes_pastas_ibfk_1` FOREIGN KEY (`es_instituicao`) REFERENCES `tb_instituicoes2` (`pr_instituicao`),
  CONSTRAINT `rl_instituicoes_pastas_ibfk_2` FOREIGN KEY (`es_pasta`) REFERENCES `tb_pastas` (`pr_pasta`),
  CONSTRAINT `rl_instituicoes_pastas_ibfk_3` FOREIGN KEY (`es_cadastrador`) REFERENCES `tb_usuarios` (`pr_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE IF NOT EXISTS `tb_alteracoes` (
  `pr_alteracao` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `en_tipo` enum('Documento','Pasta') COLLATE utf8_bin NOT NULL,
  `tx_antes` text COLLATE utf8_bin,
  `tx_depois` text COLLATE utf8_bin,
  `tx_justificativa` text COLLATE utf8_bin,
  `dt_cadastro` datetime NOT NULL,
  `es_usuario` int(10) unsigned NOT NULL,
  PRIMARY KEY (`pr_alteracao`),
  KEY `es_usuario` (`es_usuario`) USING BTREE,
  CONSTRAINT `tb_alteracoes_ibfk_1` FOREIGN KEY (`es_usuario`) REFERENCES `tb_usuarios` (`pr_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=687 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE IF NOT EXISTS `tb_documentos` (
  `pr_documento` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `es_processo` int(10) unsigned NOT NULL,
  `in_documento` int(7) unsigned zerofill NOT NULL,
  `in_unidade_sei` bigint(20) unsigned NOT NULL,
  `vc_documento` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `vc_link` varchar(500) COLLATE utf8_bin DEFAULT NULL,
  `vc_mime` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `bl_ativo` enum('0','1') COLLATE utf8_bin NOT NULL DEFAULT '1',
  `dt_desativacao` date DEFAULT NULL,
  `es_desativador` int(10) unsigned DEFAULT NULL,
  `dt_sei` datetime NOT NULL,
  PRIMARY KEY (`pr_documento`),
  UNIQUE KEY `in_documento` (`in_documento`),
  KEY `es_desativador` (`es_desativador`),
  KEY `es_processo` (`es_processo`) USING BTREE,
  CONSTRAINT `tb_documentos_ibfk_1` FOREIGN KEY (`es_processo`) REFERENCES `tb_processos` (`pr_processo`),
  CONSTRAINT `tb_documentos_ibfk_2` FOREIGN KEY (`es_desativador`) REFERENCES `tb_usuarios` (`pr_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=524183 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE IF NOT EXISTS `tb_instituicoes2` (
  `pr_instituicao` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `DDNRPESSOAFISJUR` int(10) unsigned DEFAULT NULL,
  `vc_instituicao` varchar(255) COLLATE utf8_bin NOT NULL,
  `in_tipounidade` int(1) unsigned NOT NULL DEFAULT '0',
  `vc_sigla` varchar(50) COLLATE utf8_bin NOT NULL,
  `en_sexonome` enum('m','f') COLLATE utf8_bin DEFAULT NULL,
  `bl_extinto` enum('0','1') COLLATE utf8_bin NOT NULL DEFAULT '0',
  PRIMARY KEY (`pr_instituicao`),
  UNIQUE KEY `DDNRPESSOAFISJUR` (`DDNRPESSOAFISJUR`)
) ENGINE=InnoDB AUTO_INCREMENT=1260371 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE IF NOT EXISTS `tb_pastas` (
  `pr_pasta` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `es_servidor` int(10) unsigned DEFAULT NULL,
  `in_masp` int(10) unsigned NOT NULL,
  `in_admissao` tinyint(3) unsigned NOT NULL,
  `vc_nome` varchar(100) COLLATE utf8_bin NOT NULL,
  `ch_cpf` char(11) COLLATE utf8_bin NOT NULL,
  `es_instituicao_lotacao` int(10) unsigned NOT NULL,
  `es_instituicao_exercicio` int(10) unsigned NOT NULL,
  `dt_cadastro` date DEFAULT NULL,
  `es_cadastrador` int(10) unsigned DEFAULT NULL,
  `dt_alteracao` date DEFAULT NULL,
  `es_alterador` int(10) unsigned DEFAULT NULL,
  `bl_ativo` enum('0','1') COLLATE utf8_bin NOT NULL DEFAULT '1',
  `dt_desativacao` date DEFAULT NULL,
  `es_desativador` int(10) unsigned DEFAULT NULL,
  `en_tipo` enum('servidor','estagiario','externo','serventuario','empregado_publico') COLLATE utf8_bin DEFAULT 'servidor',
  PRIMARY KEY (`pr_pasta`),
  UNIQUE KEY `es_servidor` (`es_servidor`),
  UNIQUE KEY `in_masp` (`in_masp`,`in_admissao`,`ch_cpf`,`en_tipo`) USING BTREE,
  KEY `es_instituicao_lotacao` (`es_instituicao_lotacao`),
  KEY `es_instituicao_exercicio` (`es_instituicao_exercicio`),
  CONSTRAINT `tb_pastas_ibfk_1` FOREIGN KEY (`es_instituicao_lotacao`) REFERENCES `tb_instituicoes2` (`pr_instituicao`),
  CONSTRAINT `tb_pastas_ibfk_2` FOREIGN KEY (`es_instituicao_exercicio`) REFERENCES `tb_instituicoes2` (`pr_instituicao`)
) ENGINE=InnoDB AUTO_INCREMENT=331835 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE IF NOT EXISTS `tb_pastas_publicas` (
  `pr_pasta_publica` varchar(128) COLLATE utf8_bin NOT NULL,
  `es_pasta` int(10) unsigned NOT NULL,
  `es_usuario` int(10) unsigned NOT NULL,
  `dt_cadastro` datetime NOT NULL,
  PRIMARY KEY (`pr_pasta_publica`),
  KEY `es_pasta` (`es_pasta`) USING BTREE,
  KEY `es_usuario` (`es_usuario`) USING BTREE,
  CONSTRAINT `tb_pastas_publicas_ibfk_1` FOREIGN KEY (`es_pasta`) REFERENCES `tb_pastas` (`pr_pasta`),
  CONSTRAINT `tb_pastas_publicas_ibfk_2` FOREIGN KEY (`es_usuario`) REFERENCES `tb_usuarios` (`pr_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE IF NOT EXISTS `tb_perfis` (
  `pr_perfil` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `vc_perfil` varchar(20) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`pr_perfil`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE IF NOT EXISTS `tb_processos` (
  `pr_processo` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ch_sei` varchar(255) COLLATE utf8_bin NOT NULL,
  `es_tipo_processo` int(10) unsigned NOT NULL,
  `in_codigo_sei` int(10) unsigned DEFAULT NULL,
  `vc_especificacao` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `vc_link_processo` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`pr_processo`),
  UNIQUE KEY `ch_sei` (`ch_sei`),
  KEY `es_tipo_processo` (`es_tipo_processo`),
  CONSTRAINT `tb_processos_ibfk_1` FOREIGN KEY (`es_tipo_processo`) REFERENCES `tb_tipos_processo` (`pr_tipo_processo`)
) ENGINE=InnoDB AUTO_INCREMENT=14763337 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE IF NOT EXISTS `tb_remocoes` (
  `pr_remocao` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tx_justificativa` text COLLATE utf8_bin NOT NULL,
  `dt_remocao` datetime NOT NULL,
  `es_documento` int(10) unsigned NOT NULL,
  `es_pasta` int(10) unsigned NOT NULL,
  `es_usuario` int(10) unsigned NOT NULL,
  PRIMARY KEY (`pr_remocao`),
  KEY `es_documento` (`es_documento`) USING BTREE,
  KEY `es_pasta` (`es_pasta`) USING BTREE,
  KEY `es_usuario` (`es_usuario`) USING BTREE,
  CONSTRAINT `tb_remocoes_ibfk_1` FOREIGN KEY (`es_documento`) REFERENCES `tb_documentos` (`pr_documento`),
  CONSTRAINT `tb_remocoes_ibfk_2` FOREIGN KEY (`es_pasta`) REFERENCES `tb_pastas` (`pr_pasta`),
  CONSTRAINT `tb_remocoes_ibfk_3` FOREIGN KEY (`es_usuario`) REFERENCES `tb_usuarios` (`pr_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=2292 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE IF NOT EXISTS `tb_tipos_processo` (
  `pr_tipo_processo` int(10) unsigned NOT NULL,
  `vc_tipo_processo` varchar(255) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`pr_tipo_processo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE IF NOT EXISTS `tb_usuarios` (
  `pr_usuario` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `es_instituicao` int(10) unsigned DEFAULT NULL,
  `es_perfil` int(10) unsigned NOT NULL,
  `vc_nome` varchar(250) COLLATE utf8_bin DEFAULT NULL,
  `vc_email` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `vc_telefone` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `vc_login` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `vc_senha` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `vc_senha_temporaria` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `ch_cpf` char(11) COLLATE utf8_bin NOT NULL,
  `in_unidade_sei` int(10) unsigned DEFAULT NULL,
  `dt_cadastro` date DEFAULT NULL,
  `dt_alteracao` date DEFAULT NULL,
  `dt_ultimoacesso` datetime DEFAULT NULL,
  `bl_trocasenha` enum('0','1') COLLATE utf8_bin DEFAULT '1',
  `in_erros` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `bl_removido` enum('0','1') COLLATE utf8_bin DEFAULT '0',
  PRIMARY KEY (`pr_usuario`),
  UNIQUE KEY `ch_cpf` (`ch_cpf`),
  UNIQUE KEY `vc_login` (`vc_login`),
  KEY `es_perfil` (`es_perfil`),
  KEY `es_instituicao` (`es_instituicao`),
  CONSTRAINT `tb_usuarios_ibfk_1` FOREIGN KEY (`es_instituicao`) REFERENCES `tb_instituicoes2` (`pr_instituicao`),
  CONSTRAINT `tb_usuarios_ibfk_2` FOREIGN KEY (`es_perfil`) REFERENCES `tb_perfis` (`pr_perfil`)
) ENGINE=InnoDB AUTO_INCREMENT=633 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

INSERT INTO `tb_instituicoes2` VALUES(1, NULL, 'INSTITUICAO C/ CODIGO DE UNIDADE NAO INFORMADO', 0, 'INVALIDO', 'm', '1');
INSERT INTO `tb_instituicoes2` VALUES(2, 2, 'Vice Governadoria do Estado', 2, 'VICEGOVERNADORIA', 'f', '0');
INSERT INTO `tb_instituicoes2` VALUES(9, NULL, 'Caixa de Amortização da Dívida', 8, 'CADIV', 'f', '1');
INSERT INTO `tb_instituicoes2` VALUES(1071, 1857449, 'Gabinete Militar do Governador do Estado', 4, 'GMG', 'm', '0');
INSERT INTO `tb_instituicoes2` VALUES(1081, 1858199, 'Advocacia Geral do Estado', 4, 'AGE', 'f', '0');
INSERT INTO `tb_instituicoes2` VALUES(1101, 2939378, 'Ouvidoria Geral do Estado de Minas Gerais', 4, 'OGE', 'f', '0');
INSERT INTO `tb_instituicoes2` VALUES(1111, 1857428, 'Escritório de Representação do Governo de Minas Gerais em Brasília', 4, 'ERGMG-BSB', 'm', '1');
INSERT INTO `tb_instituicoes2` VALUES(1121, 1857416, 'Secretaria do Governo', 3, 'SEGOV', 'f', '1');
INSERT INTO `tb_instituicoes2` VALUES(1141, 1791028, 'Escritório de Representação do Governo de Minas Gerais no RJ', 4, 'ERGMG-RJ', 'm', '1');
INSERT INTO `tb_instituicoes2` VALUES(1161, 1808437, 'Escritório de Representação do Governo de Minas Gerais em SP', 4, 'ERGMG-SP', 'm', '1');
INSERT INTO `tb_instituicoes2` VALUES(1171, 1858268, 'Secretaria de Estado de Recursos Humanos e Administração', 3, 'SERHA', 'f', '1');
INSERT INTO `tb_instituicoes2` VALUES(1191, 1858211, 'Secretaria de Estado de Fazenda', 3, 'SEF', 'f', '0');
INSERT INTO `tb_instituicoes2` VALUES(1201, 1858214, 'Secretaria de Estado de Planejamento e Coordenação', 3, 'SEPLAN', 'f', '1');
INSERT INTO `tb_instituicoes2` VALUES(1221, 1858203, 'Secretaria de Estado de Desenvolvimento Econômico', 3, 'SEDE', 'f', '0');
INSERT INTO `tb_instituicoes2` VALUES(1231, 1858202, 'Secretaria de Estado de Agricultura, Pecuária e Abastecimento', 3, 'SEAPA', 'f', '0');
INSERT INTO `tb_instituicoes2` VALUES(1251, 1857466, 'Polícia Militar do Estado de Minas Gerais', 4, 'PMMG', 'f', '0');
INSERT INTO `tb_instituicoes2` VALUES(1261, 1858210, 'Secretaria de Estado de Educação', 3, 'SEE', 'f', '0');
INSERT INTO `tb_instituicoes2` VALUES(1271, 1858208, 'Secretaria de Estado de Cultura e Turismo', 3, 'SECULT', 'f', '0');
INSERT INTO `tb_instituicoes2` VALUES(1281, 1858209, 'Secretaria de Esportes', 1, 'SEESP', 'f', '1');
INSERT INTO `tb_instituicoes2` VALUES(1301, 1858216, 'Secretaria de Estado de Infraestrutura e Mobilidade', 3, 'SEINFRA', 'f', '0');
INSERT INTO `tb_instituicoes2` VALUES(1311, 1858205, 'Secretaria de Indústria', 3, 'SEI', 'f', '1');
INSERT INTO `tb_instituicoes2` VALUES(1321, 1858213, 'Secretaria de Estado de Saúde', 3, 'SES', 'f', '0');
INSERT INTO `tb_instituicoes2` VALUES(1341, 1857419, 'Coordenadoria de Apoio e Assistência à Pessoa Deficiente', 4, 'CAADE', 'f', '1');
INSERT INTO `tb_instituicoes2` VALUES(1371, 1857447, 'Secretaria de Estado de Meio Ambiente e Desenvolvimento Sustentável', 3, 'SEMAD', 'f', '0');
INSERT INTO `tb_instituicoes2` VALUES(1381, 1858267, 'Secretaria de Estado do Trabalho, da Assistência Social, da Criança e do Adolescente', 3, 'SETASCAD', 'f', '1');
INSERT INTO `tb_instituicoes2` VALUES(1401, 2671339, 'Corpo de Bombeiros Militar de Minas Gerais', 4, 'CBMMG', 'm', '0');
INSERT INTO `tb_instituicoes2` VALUES(1411, 2716739, 'Secretaria de Estado de Turismo', 3, 'SETUR', 'f', '0');
INSERT INTO `tb_instituicoes2` VALUES(1421, 1791031, 'SECRETARIA COMUNICACAO SOCIAL ', 0, 'SECS', 'f', '1');
INSERT INTO `tb_instituicoes2` VALUES(1441, 2873312, 'Defensoria Pública do Estado de Minas Gerais', 4, 'DPMG', 'f', '0');
INSERT INTO `tb_instituicoes2` VALUES(1451, 1858212, 'Secretaria de Estado de Justiça e Segurança Pública', 3, 'SEJUSP', 'f', '0');
INSERT INTO `tb_instituicoes2` VALUES(1461, 2873313, 'Secretaria de Estado de Desenvolvimento Econômico', 3, 'SEDE', 'f', '1');
INSERT INTO `tb_instituicoes2` VALUES(1471, 1858204, 'Secretaria de Estado de Cidades e de Integração Regional', 3, 'SECIR', 'f', '0');
INSERT INTO `tb_instituicoes2` VALUES(1481, 2873309, 'Secretaria de Estado de Desenvolvimento Social', 3, 'SEDESE', 'f', '0');
INSERT INTO `tb_instituicoes2` VALUES(1491, 2873308, 'Secretaria de Estado de Governo', 3, 'SEGOV', 'f', '0');
INSERT INTO `tb_instituicoes2` VALUES(1501, 2873307, 'Secretaria de Estado de Planejamento e Gestão', 3, 'SEPLAG', 'f', '0');
INSERT INTO `tb_instituicoes2` VALUES(1511, 1858215, 'Polícia Civil do Estado de Minas Gerais', 4, 'PCMG', 'f', '0');
INSERT INTO `tb_instituicoes2` VALUES(1521, 2273949, 'Controladoria Geral do Estado', 4, 'CGE', 'f', '0');
INSERT INTO `tb_instituicoes2` VALUES(1531, 3017980, 'Secretaria de Estado de Esportes e da Juventude', 3, 'SEEJ', 'f', '1');
INSERT INTO `tb_instituicoes2` VALUES(1541, 3017981, 'Escola de Saúde Pública de Minas Gerais', 4, 'ESP', 'f', '0');
INSERT INTO `tb_instituicoes2` VALUES(1571, 3189804, 'Secretaria de Estado de Casa Civil e de Relações Institucionais', 3, 'SECCRI', 'f', '0');
INSERT INTO `tb_instituicoes2` VALUES(1581, 3189872, 'Secretaria de Estado de Trabalho e Emprego', 3, 'SETE', 'f', '1');
INSERT INTO `tb_instituicoes2` VALUES(1591, 3189873, 'Secretaria de Estado de Desenvolvimento e Integração do Norte e Nordeste de Minas Gerais', 3, 'SEDINOR', 'f', '0');
INSERT INTO `tb_instituicoes2` VALUES(1601, 3190139, 'Escritório de Prioridades Estratégicas', 3, 'EPE', 'm', '1');
INSERT INTO `tb_instituicoes2` VALUES(1631, 3200569, 'Secretaria Geral', 1, 'SG', 'f', '0');
INSERT INTO `tb_instituicoes2` VALUES(1641, 3347491, 'Secretaria de Estado de Desenvolvimento Agrário', 3, 'SEDA', 'f', '0');
INSERT INTO `tb_instituicoes2` VALUES(1651, 3346710, 'Secretaria de Estado de Direitos Humanos, Participação Social e Cidadania', 3, 'SEDPAC', 'f', '0');
INSERT INTO `tb_instituicoes2` VALUES(1671, 3347506, 'Secretaria de Estado de Esportes ', 3, 'SEESP', 'f', '0');
INSERT INTO `tb_instituicoes2` VALUES(1691, 3408097, 'Secretaria de Estado de Segurança Pública', 3, 'SESP', 'f', '0');
INSERT INTO `tb_instituicoes2` VALUES(1692, NULL, 'INSTITUICAO C/ CODIGO DE UNIDADE NAO INFORMADO', 0, 'INVALIDO', 'm', '1');
INSERT INTO `tb_instituicoes2` VALUES(1693, NULL, 'INSTITUICAO C/ CODIGO DE UNIDADE NAO INFORMADO', 0, 'INVALIDO', 'm', '1');
INSERT INTO `tb_instituicoes2` VALUES(1701, 3414458, 'Secretaria de Estado Extraordinária de Desenvolvimento Integrado', 3, 'SEEDIF', 'f', '1');
INSERT INTO `tb_instituicoes2` VALUES(1941, 1853802, 'ENCARGOS GERAIS PLANEJAMENTO E GESTAO', 0, 'ENCARGOS', 'm', '1');
INSERT INTO `tb_instituicoes2` VALUES(2011, 1857455, 'Instituto de Previdência dos Servidores do Estado de Minas Gerais', 6, 'IPSEMG', 'm', '0');
INSERT INTO `tb_instituicoes2` VALUES(2041, 1857459, 'Loteria do Estado de Minas Gerais', 6, 'LEMG', 'f', '0');
INSERT INTO `tb_instituicoes2` VALUES(2061, 1857440, 'Fundação João Pinheiro', 5, 'FJP', 'f', '0');
INSERT INTO `tb_instituicoes2` VALUES(2071, 1857430, 'Fundação de Amparo a Pesquisa do Estado de Minas Gerais', 5, 'FAPEMIG', 'f', '0');
INSERT INTO `tb_instituicoes2` VALUES(2081, 1857417, 'Fundação Centro Tecnológico de Minas Gerais', 5, 'CETEC', 'f', '1');
INSERT INTO `tb_instituicoes2` VALUES(2091, 2273955, 'Fundação Estadual do Meio Ambiente', 5, 'FEAM', 'f', '0');
INSERT INTO `tb_instituicoes2` VALUES(2101, 1767133, 'Instituto Estadual de Florestas', 6, 'IEF', 'm', '0');
INSERT INTO `tb_instituicoes2` VALUES(2111, 1858201, 'Fundação Rural Mineira', 5, 'RURALMINAS', 'f', '1');
INSERT INTO `tb_instituicoes2` VALUES(2121, 1857454, 'Instituto de Previdência dos Servidores Militares do Estado de Minas Gerais', 6, 'IPSM', 'm', '0');
INSERT INTO `tb_instituicoes2` VALUES(2141, 1857422, 'Departamento de Obras Públicas do Estado de Minas Gerais', 6, 'DEOP', 'm', '1');
INSERT INTO `tb_instituicoes2` VALUES(2151, 1857438, 'Fundação Helena Antipoff', 5, 'FHA', 'f', '0');
INSERT INTO `tb_instituicoes2` VALUES(2161, 1857434, 'Fundação Educacional Caio Martins', 5, 'FUCAM', 'f', '0');
INSERT INTO `tb_instituicoes2` VALUES(2171, 1857429, 'Fundação de Arte de Ouro Preto', 5, 'FAOP', 'f', '0');
INSERT INTO `tb_instituicoes2` VALUES(2181, 1857432, 'Fundação Clóvis Salgado', 5, 'FCS', 'f', '0');
INSERT INTO `tb_instituicoes2` VALUES(2201, 1857451, 'Instituto Estadual do Patrimônio Histórico e Artístico de Minas Gerais', 5, 'IEPHA', 'm', '0');
INSERT INTO `tb_instituicoes2` VALUES(2211, 1858561, 'Fundação TV Minas', 5, 'TVMINAS', 'f', '0');
INSERT INTO `tb_instituicoes2` VALUES(2231, 1853800, 'Administração de Estádios do Estado de Minas Gerais', 6, 'ADEMG', 'f', '1');
INSERT INTO `tb_instituicoes2` VALUES(2241, 2273951, 'Instituto Mineiro de Gestão das Águas', 6, 'IGAM', 'm', '0');
INSERT INTO `tb_instituicoes2` VALUES(2251, 1857457, 'Junta Comercial do Estado de Minas Gerais', 6, 'JUCEMG', 'f', '0');
INSERT INTO `tb_instituicoes2` VALUES(2261, 1857448, 'Fundação Ezequiel Dias', 5, 'FUNED', 'f', '0');
INSERT INTO `tb_instituicoes2` VALUES(2271, 1857431, 'Fundação Hospitalar do Estado de Minas Gerais', 5, 'FHEMIG', 'f', '0');
INSERT INTO `tb_instituicoes2` VALUES(2281, 1858563, 'Fundação de Educação para o Trabalho de Minas Gerais', 5, 'UTRAMIG', 'f', '0');
INSERT INTO `tb_instituicoes2` VALUES(2301, 1857424, 'Departamento de Edificações e Estradas de Rodagem do Estado de Minas Gerais', 6, 'DEER', 'm', '0');
INSERT INTO `tb_instituicoes2` VALUES(2311, 1858562, 'Universidade Estadual de Montes Claros', 6, 'UNIMONTES', 'f', '0');
INSERT INTO `tb_instituicoes2` VALUES(2321, 1857439, 'Fundação Centro de Hematologia e Hemoterapia do Estado de Minas Gerais', 5, 'HEMOMINAS', 'f', '0');
INSERT INTO `tb_instituicoes2` VALUES(2331, 1857456, 'Instituto de Metrologia e Qualidade', 6, 'IPEM', 'm', '0');
INSERT INTO `tb_instituicoes2` VALUES(2351, 1806206, 'Universidade do Estado de Minas Gerais', 6, 'UEMG', 'f', '0');
INSERT INTO `tb_instituicoes2` VALUES(2371, 1862379, 'Instituto Mineiro de Agropecuária', 6, 'IMA', 'm', '0');
INSERT INTO `tb_instituicoes2` VALUES(2381, 1857423, 'Departamento Estadual de Telecomunicações de Minas Gerais', 6, 'DETEL', 'm', '1');
INSERT INTO `tb_instituicoes2` VALUES(2391, 1857452, 'Imprensa Oficial do Estado de Minas Gerais', 6, 'IOF', 'f', '1');
INSERT INTO `tb_instituicoes2` VALUES(2401, 2426354, 'Instituto de Geoinformação e Tecnologia', 6, 'IGTEC', 'm', '1');
INSERT INTO `tb_instituicoes2` VALUES(2411, 2855048, 'Instituto de Terras do Estado de Minas Gerais', 6, 'ITER', 'm', '1');
INSERT INTO `tb_instituicoes2` VALUES(2421, 1857418, 'Instituto de Desenvolvimento do Norte e Nordeste de Minas Gerais', 6, 'IDENE', 'm', '0');
INSERT INTO `tb_instituicoes2` VALUES(2431, 3115953, 'Agência de Desenvolvimento da Região Metropolitana de Belo Horizonte', 6, 'ARMBH', 'f', '0');
INSERT INTO `tb_instituicoes2` VALUES(2441, 3133385, 'Agência Reguladora de Serviços de Abastecimento de Água e de Esgotamento Sanitário do Estado de Minas Gerais', 6, 'ARSAE', 'f', '0');
INSERT INTO `tb_instituicoes2` VALUES(2451, 3146991, 'Fundação Centro Internacional de Educação Capacitação e Pesquisa Aplicada em Águas', 5, 'HIDROEX', 'f', '1');
INSERT INTO `tb_instituicoes2` VALUES(2461, 3230042, 'Agência Metropolitana do Vale do Aço', 6, 'ARMVA', 'f', '0');
INSERT INTO `tb_instituicoes2` VALUES(3001, NULL, 'Secretaria de Estado Extraordinária para Assuntos de Reforma Agrária', 3, 'SEARA', 'f', '1');
INSERT INTO `tb_instituicoes2` VALUES(3041, 2426998, 'Empresa de Assistência Técnica e Extensão Rural do Estado de Minas Gerais', 8, 'EMATER', 'f', '0');
INSERT INTO `tb_instituicoes2` VALUES(3051, 2426999, 'Empresa de Pesquisa Agropecuária de Minas Gerais', 8, 'EPAMIG', 'f', '0');
INSERT INTO `tb_instituicoes2` VALUES(3151, 1858200, 'Empresa Mineira de Comunicação', 8, 'EMC', 'f', '0');
INSERT INTO `tb_instituicoes2` VALUES(5011, 2426992, 'Companhia de Desenvolvimento Econômico de Minas Gerais', 8, 'CODEMIG', 'f', '0');
INSERT INTO `tb_instituicoes2` VALUES(5031, NULL, 'Companhia de Desenvolvimento de Minas Gerais', 8, 'CODEMGE', 'f', '0');
INSERT INTO `tb_instituicoes2` VALUES(5071, 2426982, 'Companhia de Habitação do Estado de Minas Gerais', 8, 'COHAB', 'f', '0');
INSERT INTO `tb_instituicoes2` VALUES(5080, NULL, 'Companhia de Saneamento de Minas Gerais', 8, 'COPASA', 'f', '0');
INSERT INTO `tb_instituicoes2` VALUES(5121, 2426989, 'Companhia Energética de Minas Gerais', 8, 'CEMIG', 'f', '0');
INSERT INTO `tb_instituicoes2` VALUES(5131, 2427004, 'Instituto de Desenvolvimento Integrado de Minas Gerais', 6, 'INDI', 'm', '0');
INSERT INTO `tb_instituicoes2` VALUES(5141, 1764024, 'Companhia de Tecnologia da Informação do Estado de Minas Gerais', 8, 'PRODEMGE', 'f', '0');
INSERT INTO `tb_instituicoes2` VALUES(5181, 2426997, 'Distribuidora de Títulos e Valores Mobiliários de Minas Gerais S.A.', 8, 'DIMINAS', 'f', '1');
INSERT INTO `tb_instituicoes2` VALUES(5191, 2427005, 'Minas Gerais Participações S.A.', 8, 'MGI', 'f', '0');
INSERT INTO `tb_instituicoes2` VALUES(5201, 2426925, 'Banco de Desenvolvimento de Minas Gerais S.A.', 8, 'BDMG', 'm', '0');
INSERT INTO `tb_instituicoes2` VALUES(5241, 2426990, 'Companhia Mineira de Promoções', 8, 'PROMINAS', 'f', '0');
INSERT INTO `tb_instituicoes2` VALUES(5251, NULL, 'Companhia de Gás de Minas Gerais', 8, 'GASMIG', 'f', '0');
INSERT INTO `tb_instituicoes2` VALUES(5261, NULL, 'Trem Metropolitano de Belo Horizonte S.A.', 8, 'METROMINAS', 'f', '0');
INSERT INTO `tb_instituicoes2` VALUES(5381, 2427008, 'Minas Gerais Administração e Serviços S.A.', 8, 'MGS', 'f', '0');
INSERT INTO `tb_instituicoes2` VALUES(5391, NULL, 'CEMIG Geração e Transmissão S.A.', 8, 'CEMIG GERAÇÃO E TRANSMISSÃO', 'f', '0');
INSERT INTO `tb_instituicoes2` VALUES(5401, NULL, 'CEMIG DISTRIBUIÇÃO S.A.', 8, 'CEMIG DISTRIBUIDORA', 'f', '0');
INSERT INTO `tb_instituicoes2` VALUES(5511, NULL, 'COPASA - SERVIÇOS DE SANEAMENTO INTEGRADO DO NORTE E NORDESTE DE MINAS GERAIS S/A', 0, 'COPANOR', 'f', '0');
INSERT INTO `tb_instituicoes2` VALUES(1260365, NULL, 'Secretaria de Estado Extraordinária da Copa do Mundo', 3, 'SECOPA', 'f', '1');
INSERT INTO `tb_instituicoes2` VALUES(1260366, NULL, 'Secretaria de Estado Extraordinária de Gestão Metropolitana', 3, 'SEEGM', 'f', '1');
INSERT INTO `tb_instituicoes2` VALUES(1260367, NULL, 'Secretaria de Estado Extraordinária de Regularização Fundiária', 3, 'SEERF', 'f', '1');
INSERT INTO `tb_instituicoes2` VALUES(1260368, NULL, 'Conselho Estadual de Educação', 8, 'CEE', 'm', '0');
INSERT INTO `tb_instituicoes2` VALUES(1260370, NULL, 'INSTITUICAO C/ CODIGO DE UNIDADE NAO INFORMADO', 0, 'INVALIDO', 'm', '1');

INSERT INTO `tb_perfis` VALUES(1, 'RH Visualizador');
INSERT INTO `tb_perfis` VALUES(2, 'Geral Visualizador');
INSERT INTO `tb_perfis` VALUES(3, 'Administrador');
INSERT INTO `tb_perfis` VALUES(4, 'Desativado');
INSERT INTO `tb_perfis` VALUES(5, 'RH Arquivo');

INSERT INTO `tb_tipos_processo` VALUES(100000101, 'RH: Abono de Permanência');
INSERT INTO `tb_tipos_processo` VALUES(100000102, 'Pessoal: Adicional de Férias (1/3 constitucional)');
INSERT INTO `tb_tipos_processo` VALUES(100000103, 'RH: Adicional de Insalubridade');
INSERT INTO `tb_tipos_processo` VALUES(100000104, 'Pessoal: Adicional de Periculosidade');
INSERT INTO `tb_tipos_processo` VALUES(100000105, 'Pessoal: Adicional Noturno');
INSERT INTO `tb_tipos_processo` VALUES(100000106, 'Pessoal: Adicional por Atividade Penosa');
INSERT INTO `tb_tipos_processo` VALUES(100000107, 'Pessoal: Adicional por Serviço Extraordinário');
INSERT INTO `tb_tipos_processo` VALUES(100000108, 'Pessoal: Adicional de Desempenho (ADE)');
INSERT INTO `tb_tipos_processo` VALUES(100000109, 'Pessoal: Afastamento para Atividade Desportiva');
INSERT INTO `tb_tipos_processo` VALUES(100000110, 'Pessoal: Afastamento para Curso na Escola de Gover');
INSERT INTO `tb_tipos_processo` VALUES(100000111, 'Pessoal: Afastamento para Depor');
INSERT INTO `tb_tipos_processo` VALUES(100000112, 'Pessoal: Afastamento para Exercer Mandato Eletivo');
INSERT INTO `tb_tipos_processo` VALUES(100000113, 'Pessoal: Requisição para Serviço Eleitoral (TRE)');
INSERT INTO `tb_tipos_processo` VALUES(100000114, 'Pessoal: Afastamento para Servir como Jurado');
INSERT INTO `tb_tipos_processo` VALUES(100000116, 'Pessoal: Afastamento para Estudos');
INSERT INTO `tb_tipos_processo` VALUES(100000118, 'Pessoal: Afastamento para servir em Organismo Inte');
INSERT INTO `tb_tipos_processo` VALUES(100000119, 'Pessoal: Ajuda de Custo com Mudança de Domicílio');
INSERT INTO `tb_tipos_processo` VALUES(100000120, 'Pessoal: Aposentadoria Compulsória');
INSERT INTO `tb_tipos_processo` VALUES(100000121, 'Pessoal: Aposentadoria por Invalidez');
INSERT INTO `tb_tipos_processo` VALUES(100000122, 'Pessoal: Aposentadoria - Pensão Vitalícia');
INSERT INTO `tb_tipos_processo` VALUES(100000123, 'Pessoal: Assentamento Funcional do Servidor');
INSERT INTO `tb_tipos_processo` VALUES(100000124, 'Pessoal: Saúde - Solicitação de Auxílio-Saúde');
INSERT INTO `tb_tipos_processo` VALUES(100000125, 'Pessoal: Saúde - Plano de Saúde');
INSERT INTO `tb_tipos_processo` VALUES(100000126, 'Pessoal: Saúde - Prontuário Médico');
INSERT INTO `tb_tipos_processo` VALUES(100000127, 'Pessoal: Ausência em razão de Casamento');
INSERT INTO `tb_tipos_processo` VALUES(100000128, 'Pessoal: Ausência para Alistamento Eleitoral');
INSERT INTO `tb_tipos_processo` VALUES(100000129, 'Pessoal: Ausência para Doação de Sangue');
INSERT INTO `tb_tipos_processo` VALUES(100000130, 'Pessoal: Ausência por Falecimento de Familiar');
INSERT INTO `tb_tipos_processo` VALUES(100000131, 'Pessoal: Auxílio Acidente');
INSERT INTO `tb_tipos_processo` VALUES(100000132, 'vantagens');
INSERT INTO `tb_tipos_processo` VALUES(100000133, 'Pessoal: Auxílio Assistência Pré-Escolar/Creche');
INSERT INTO `tb_tipos_processo` VALUES(100000134, 'Pessoal: Auxílio Doença');
INSERT INTO `tb_tipos_processo` VALUES(100000135, 'Pessoal: Auxílio Funeral');
INSERT INTO `tb_tipos_processo` VALUES(100000136, 'Pessoal: Auxílio Moradia');
INSERT INTO `tb_tipos_processo` VALUES(100000137, 'Pessoal: Auxílio Natalidade');
INSERT INTO `tb_tipos_processo` VALUES(100000138, 'Pessoal: Auxílio Reclusão');
INSERT INTO `tb_tipos_processo` VALUES(100000139, 'Pessoal: Auxílio-Transporte');
INSERT INTO `tb_tipos_processo` VALUES(100000140, 'Pessoal: Avaliação de Desempenho Individual');
INSERT INTO `tb_tipos_processo` VALUES(100000141, 'Pessoal: Avaliação de Desempenho Institucional');
INSERT INTO `tb_tipos_processo` VALUES(100000142, 'Pessoal: Estágio Probatório');
INSERT INTO `tb_tipos_processo` VALUES(100000143, 'Pessoal: Averbação de Tempo de Serviço');
INSERT INTO `tb_tipos_processo` VALUES(100000144, 'Pessoal: Bolsa de Estudo de Idioma Estrangeiro');
INSERT INTO `tb_tipos_processo` VALUES(100000145, 'Pessoal: Bolsa de Pós-Graduação');
INSERT INTO `tb_tipos_processo` VALUES(100000146, 'Pessoal: Cadastro de Dependente no Imposto de Rend');
INSERT INTO `tb_tipos_processo` VALUES(100000147, 'Pessoal: Apresentação de Certificado de Curso');
INSERT INTO `tb_tipos_processo` VALUES(100000148, 'RH: Cessão de Servidor');
INSERT INTO `tb_tipos_processo` VALUES(100000149, 'RH: Contagem de Tempo/Averbação : Apuração de Tempo de Serviço');
INSERT INTO `tb_tipos_processo` VALUES(100000150, 'Pessoal: Coleta de Imagem de Assinatura');
INSERT INTO `tb_tipos_processo` VALUES(100000151, 'RH: Aposentadoria');
INSERT INTO `tb_tipos_processo` VALUES(100000152, 'Pessoal: Concurso Público - Exames Admissionais');
INSERT INTO `tb_tipos_processo` VALUES(100000153, 'Pessoal: Concurso Público - Organização');
INSERT INTO `tb_tipos_processo` VALUES(100000154, 'Pessoal: Concurso Público - Provas e Títulos');
INSERT INTO `tb_tipos_processo` VALUES(100000157, 'Pessoal: Frequência');
INSERT INTO `tb_tipos_processo` VALUES(100000158, 'Pessoal: Curso no Exterior - com ônus');
INSERT INTO `tb_tipos_processo` VALUES(100000159, 'Pessoal: Cursos Promovidos');
INSERT INTO `tb_tipos_processo` VALUES(100000160, 'Pessoal: Curso Promovido por outra Instituição');
INSERT INTO `tb_tipos_processo` VALUES(100000161, 'Pessoal: Curso de Pós-Graduação');
INSERT INTO `tb_tipos_processo` VALUES(100000162, 'Pessoal: Delegação de Competência');
INSERT INTO `tb_tipos_processo` VALUES(100000163, 'RH: Opção de Contribuição Previdenciária');
INSERT INTO `tb_tipos_processo` VALUES(100000164, 'Pessoal: Desconto de Contribuição Associativa');
INSERT INTO `tb_tipos_processo` VALUES(100000165, 'Pessoal: Desconto de Contribuição Sindical');
INSERT INTO `tb_tipos_processo` VALUES(100000166, 'Pessoal: Consignação em Folha de Pagamento');
INSERT INTO `tb_tipos_processo` VALUES(100000167, 'Pessoal: Desconto de Pensão Alimentícia');
INSERT INTO `tb_tipos_processo` VALUES(100000169, 'Pessoal: Desconto do IRPF Retido na Fonte');
INSERT INTO `tb_tipos_processo` VALUES(100000170, 'Pessoal: Designação');
INSERT INTO `tb_tipos_processo` VALUES(100000172, 'Pessoal: Emissão de Certidões e Declarações');
INSERT INTO `tb_tipos_processo` VALUES(100000173, 'Pessoal: Emissão de Procuração');
INSERT INTO `tb_tipos_processo` VALUES(100000174, 'Pessoal: Encargo Patronal - Contribuição para INSS');
INSERT INTO `tb_tipos_processo` VALUES(100000175, 'Pessoal: Estágio - Dossiê do Estagiário');
INSERT INTO `tb_tipos_processo` VALUES(100000176, 'Pessoal: Estágio - Planejamento/Organização Geral');
INSERT INTO `tb_tipos_processo` VALUES(100000177, 'Pessoal: Estágio de Servidor no Brasil');
INSERT INTO `tb_tipos_processo` VALUES(100000178, 'Pessoal: Contratação de Estagiários');
INSERT INTO `tb_tipos_processo` VALUES(100000179, 'RH: Exoneração de Cargo Efetivo ou Dispensa de Função Pública');
INSERT INTO `tb_tipos_processo` VALUES(100000181, 'Pessoal: Falecimento de Servidor');
INSERT INTO `tb_tipos_processo` VALUES(100000182, 'Pessoal: Férias Regulamentares');
INSERT INTO `tb_tipos_processo` VALUES(100000183, 'Pessoal: Férias - Interrupção');
INSERT INTO `tb_tipos_processo` VALUES(100000184, 'Pessoal: Férias - Solicitação');
INSERT INTO `tb_tipos_processo` VALUES(100000185, 'Pessoal: Ficha Financeira (Contracheque)');
INSERT INTO `tb_tipos_processo` VALUES(100000186, 'RH: Folha de Pagamento');
INSERT INTO `tb_tipos_processo` VALUES(100000187, 'Pessoal: Gratificação de Desempenho');
INSERT INTO `tb_tipos_processo` VALUES(100000188, 'Pessoal: Gratificação Natalina (Décimo Terceiro)');
INSERT INTO `tb_tipos_processo` VALUES(100000189, 'Pessoal: Gratificação por Encargo - Curso/Concurso');
INSERT INTO `tb_tipos_processo` VALUES(100000190, 'Pessoal: Horário de Expediente - Definição');
INSERT INTO `tb_tipos_processo` VALUES(100000191, 'Pessoal: Horário de Expediente - Escala de Plantão');
INSERT INTO `tb_tipos_processo` VALUES(100000192, 'Pessoal: Horário Especial - Familiar Deficiente');
INSERT INTO `tb_tipos_processo` VALUES(100000193, 'Pessoal: Horário Especial - Instrutor de Curso');
INSERT INTO `tb_tipos_processo` VALUES(100000194, 'Pessoal: Horário Especial - Servidor Deficiente');
INSERT INTO `tb_tipos_processo` VALUES(100000195, 'RH: Flexibilização de Horário de Trabalho');
INSERT INTO `tb_tipos_processo` VALUES(100000196, 'Pessoal: Indenização de Transporte (meio próprio)');
INSERT INTO `tb_tipos_processo` VALUES(100000197, 'Pessoal: Saúde - Inspeção Periódica');
INSERT INTO `tb_tipos_processo` VALUES(100000198, 'RH: Licença Adotante');
INSERT INTO `tb_tipos_processo` VALUES(100000199, 'Pessoal: Licença Maternidade');
INSERT INTO `tb_tipos_processo` VALUES(100000200, 'Pessoal: Licença Paternidade');
INSERT INTO `tb_tipos_processo` VALUES(100000201, 'Pessoal: Licença para Atividade Política');
INSERT INTO `tb_tipos_processo` VALUES(100000202, 'Pessoal: Licença para Capacitação');
INSERT INTO `tb_tipos_processo` VALUES(100000203, 'Pessoal: Licença para Mandato Classista');
INSERT INTO `tb_tipos_processo` VALUES(100000204, 'Pessoal: Licença para Serviço Militar');
INSERT INTO `tb_tipos_processo` VALUES(100000205, 'Pessoal: Licença para Tratamento da Própria Saúde');
INSERT INTO `tb_tipos_processo` VALUES(100000206, 'Pessoal: Licença por Acidente em Serviço');
INSERT INTO `tb_tipos_processo` VALUES(100000207, 'Pessoal: Licença por Afastamento do Cônjuge');
INSERT INTO `tb_tipos_processo` VALUES(100000208, 'Pessoal: Licença por Doença em Pessoa da Família');
INSERT INTO `tb_tipos_processo` VALUES(100000209, 'Pessoal: Licença por Doença Profissional');
INSERT INTO `tb_tipos_processo` VALUES(100000210, 'Pessoal: Licença Prêmio por Assiduidade');
INSERT INTO `tb_tipos_processo` VALUES(100000211, 'RH: Licença para Tratar de Interesses Particulares (LIP)');
INSERT INTO `tb_tipos_processo` VALUES(100000212, 'Pessoal: Licenças por Aborto/Natimorto');
INSERT INTO `tb_tipos_processo` VALUES(100000213, 'Pessoal: Movimentação de Servidor');
INSERT INTO `tb_tipos_processo` VALUES(100000214, 'Pessoal: Movimento Reivindicatório');
INSERT INTO `tb_tipos_processo` VALUES(100000215, 'Pessoal: Negociação Sindical');
INSERT INTO `tb_tipos_processo` VALUES(100000217, 'Pessoal: Normatização');
INSERT INTO `tb_tipos_processo` VALUES(100000218, 'Pessoal: Ocupação de Imóvel Funcional');
INSERT INTO `tb_tipos_processo` VALUES(100000219, 'Pessoal: Orientações e Diretrizes Gerais');
INSERT INTO `tb_tipos_processo` VALUES(100000220, 'Pessoal: Pagamento de Provento');
INSERT INTO `tb_tipos_processo` VALUES(100000221, 'Pessoal: Pagamento de Remuneração');
INSERT INTO `tb_tipos_processo` VALUES(100000222, 'Pessoal: Penalidade Advertência');
INSERT INTO `tb_tipos_processo` VALUES(100000223, 'Pessoal: Penalidade Cassação de Aposentadoria');
INSERT INTO `tb_tipos_processo` VALUES(100000224, 'Pessoal: Penalidade');
INSERT INTO `tb_tipos_processo` VALUES(100000225, 'Pessoal: Penalidade Destituição Cargo em Comissão');
INSERT INTO `tb_tipos_processo` VALUES(100000226, 'Pessoal: Penalidade Disponibilidade');
INSERT INTO `tb_tipos_processo` VALUES(100000227, 'Pessoal: Penalidade Suspensão');
INSERT INTO `tb_tipos_processo` VALUES(100000228, 'Pessoal: Pensão IPSEMG');
INSERT INTO `tb_tipos_processo` VALUES(100000229, 'Pessoal: Planejamento da Força de Trabalho');
INSERT INTO `tb_tipos_processo` VALUES(100000230, 'RH: Prêmio Inova Minas Gerais');
INSERT INTO `tb_tipos_processo` VALUES(100000231, 'Pessoal: Prevenção de Acidentes no Trabalho');
INSERT INTO `tb_tipos_processo` VALUES(100000232, 'RH: Progressão e Promoção');
INSERT INTO `tb_tipos_processo` VALUES(100000233, 'Pessoal: Progressão e Promoção (Quadro Específico)');
INSERT INTO `tb_tipos_processo` VALUES(100000234, 'Pessoal: Provimento - Nomeação para Cargo Efetivo');
INSERT INTO `tb_tipos_processo` VALUES(100000235, 'Pessoal: Provimento - Nomeação para Cargo em Comissão');
INSERT INTO `tb_tipos_processo` VALUES(100000236, 'Pessoal: Provimento - por Aproveitamento');
INSERT INTO `tb_tipos_processo` VALUES(100000237, 'Pessoal: Provimento - por Readaptação, Recondução ou Reintegração');
INSERT INTO `tb_tipos_processo` VALUES(100000238, 'Pessoal: Provimento - por Recondução');
INSERT INTO `tb_tipos_processo` VALUES(100000239, 'Pessoal: Provimento - por Reintegração');
INSERT INTO `tb_tipos_processo` VALUES(100000240, 'Pessoal: Provimento - por Reversão');
INSERT INTO `tb_tipos_processo` VALUES(100000242, 'Pessoal: Relação com Conselho Profissional');
INSERT INTO `tb_tipos_processo` VALUES(100000243, 'RH: Remoção');
INSERT INTO `tb_tipos_processo` VALUES(100000244, 'Pessoal: Remoção a Pedido com Mudança de Sede');
INSERT INTO `tb_tipos_processo` VALUES(100000245, 'Pessoal: Remoção a Pedido para Acompanhar Cônjuge');
INSERT INTO `tb_tipos_processo` VALUES(100000246, 'Pessoal: Remoção a Pedido por Motivo de Saúde');
INSERT INTO `tb_tipos_processo` VALUES(100000247, 'Pessoal: Remoção a Pedido sem Mudança de Sede');
INSERT INTO `tb_tipos_processo` VALUES(100000248, 'Pessoal: Remoção de Ofício com Mudança de Sede');
INSERT INTO `tb_tipos_processo` VALUES(100000249, 'Pessoal: Remoção de Ofício sem Mudança de Sede');
INSERT INTO `tb_tipos_processo` VALUES(100000250, 'Pessoal: Requisição de Servidor Externo');
INSERT INTO `tb_tipos_processo` VALUES(100000251, 'Pessoal: Requisição de Servidor Interno');
INSERT INTO `tb_tipos_processo` VALUES(100000252, 'Pessoal: Restruturação de Cargos e Funções');
INSERT INTO `tb_tipos_processo` VALUES(100000253, 'Pessoal: Retribuição por Cargo em Comissão');
INSERT INTO `tb_tipos_processo` VALUES(100000254, 'Pessoal: Salário-Família');
INSERT INTO `tb_tipos_processo` VALUES(100000255, 'Pessoal: Informações à AGE para Subsídio à Defesa ');
INSERT INTO `tb_tipos_processo` VALUES(100000256, 'Arrecadação: Cobrança');
INSERT INTO `tb_tipos_processo` VALUES(100000261, 'Arrecadação: Receita');
INSERT INTO `tb_tipos_processo` VALUES(100000267, 'Contabilidade: Contratos e Garantias');
INSERT INTO `tb_tipos_processo` VALUES(100000270, 'Contabilidade: Encerramento do Exercício');
INSERT INTO `tb_tipos_processo` VALUES(100000271, 'Controle de Estoque');
INSERT INTO `tb_tipos_processo` VALUES(100000275, 'Prestação de Contas Anual para o TCEMG - Administr');
INSERT INTO `tb_tipos_processo` VALUES(100000278, 'Gestão da Informação: Arrecadação');
INSERT INTO `tb_tipos_processo` VALUES(100000279, 'Orçamento: Acompanhamento de Despesa Mensal');
INSERT INTO `tb_tipos_processo` VALUES(100000282, 'Orçamento: Descentralização de Créditos');
INSERT INTO `tb_tipos_processo` VALUES(100000283, 'Orçamento: Manuais');
INSERT INTO `tb_tipos_processo` VALUES(100000284, 'Orçamento: Programação Orçamentária');
INSERT INTO `tb_tipos_processo` VALUES(100000285, 'Viagem a Serviço: Sem ônus para instituição');
INSERT INTO `tb_tipos_processo` VALUES(100000289, 'Pessoal: Vacância');
INSERT INTO `tb_tipos_processo` VALUES(100000291, 'Material: Desfazimento de Material Permanente');
INSERT INTO `tb_tipos_processo` VALUES(100000292, 'Material: Desfazimento de Material de Consumo');
INSERT INTO `tb_tipos_processo` VALUES(100000293, 'Material: Movimentação de Material de Consumo');
INSERT INTO `tb_tipos_processo` VALUES(100000294, 'Inventários: Material de Consumo');
INSERT INTO `tb_tipos_processo` VALUES(100000295, 'Inventários: de Material Permanente');
INSERT INTO `tb_tipos_processo` VALUES(100000296, 'Patrimônio: Gestão de Bens Imóveis');
INSERT INTO `tb_tipos_processo` VALUES(100000298, 'Controle de Portaria/Acesso');
INSERT INTO `tb_tipos_processo` VALUES(100000303, 'Procedimentos Administrativos Disciplinares');
INSERT INTO `tb_tipos_processo` VALUES(100000304, 'Licitação: Plano de Aquisições');
INSERT INTO `tb_tipos_processo` VALUES(100000305, 'Convênios/Ajustes: Formalização/Alteração com Repa');
INSERT INTO `tb_tipos_processo` VALUES(100000306, 'Convênios/Ajustes: Formalização/Alteração sem Repasse');
INSERT INTO `tb_tipos_processo` VALUES(100000307, 'Convênios/Ajustes: Acompanhamento da Execução');
INSERT INTO `tb_tipos_processo` VALUES(100000308, 'Gestão de Contrato: Supressão Contratual');
INSERT INTO `tb_tipos_processo` VALUES(100000309, 'Gestão de Contrato: Aplicação de Sanção Contratual');
INSERT INTO `tb_tipos_processo` VALUES(100000310, 'Gestão de Contrato: Revisão Contratual');
INSERT INTO `tb_tipos_processo` VALUES(100000311, 'Gestão de Contrato: Execução de Garantia');
INSERT INTO `tb_tipos_processo` VALUES(100000312, 'Gestão de Contrato: Processo de Pagamento');
INSERT INTO `tb_tipos_processo` VALUES(100000313, 'Gestão de Contrato: Prorrogação Contratual');
INSERT INTO `tb_tipos_processo` VALUES(100000314, 'Gestão de Contrato: Reajuste ou Repactuação Contra');
INSERT INTO `tb_tipos_processo` VALUES(100000315, 'Gestão de Contrato: Rescisão Contratual');
INSERT INTO `tb_tipos_processo` VALUES(100000316, 'Gestão de Contrato: Acompanhamento da Execução');
INSERT INTO `tb_tipos_processo` VALUES(100000317, 'Licitação: Pregão');
INSERT INTO `tb_tipos_processo` VALUES(100000318, 'Licitação: Pregão para Registro de Preço');
INSERT INTO `tb_tipos_processo` VALUES(100000319, 'Licitação: Pregão Presencial');
INSERT INTO `tb_tipos_processo` VALUES(100000320, 'Licitação: Concorrência');
INSERT INTO `tb_tipos_processo` VALUES(100000321, 'Licitação: Concorrência-Registro de Preço');
INSERT INTO `tb_tipos_processo` VALUES(100000322, 'Licitação: Tomada de Preços');
INSERT INTO `tb_tipos_processo` VALUES(100000323, 'Licitação: Convite');
INSERT INTO `tb_tipos_processo` VALUES(100000324, 'Licitação: Regime Diferenciado de Contratação-RDC');
INSERT INTO `tb_tipos_processo` VALUES(100000325, 'Licitação para Contratação de Serviço: Pregão Elet');
INSERT INTO `tb_tipos_processo` VALUES(100000326, 'Licitação: Leilão');
INSERT INTO `tb_tipos_processo` VALUES(100000328, 'Licitação: Adesão a Ata de RP-Participante');
INSERT INTO `tb_tipos_processo` VALUES(100000329, 'Licitação: Adesão a Ata de RP-Não Participante');
INSERT INTO `tb_tipos_processo` VALUES(100000330, 'Licitação: Dispensa - Até R$ 8 mil');
INSERT INTO `tb_tipos_processo` VALUES(100000331, 'Licitação: Dispensa - Acima de R$ 8 mil');
INSERT INTO `tb_tipos_processo` VALUES(100000332, 'Licitação: Inexigibilidade');
INSERT INTO `tb_tipos_processo` VALUES(100000333, 'Ouvidoria: Elogio à atuação do Órgão');
INSERT INTO `tb_tipos_processo` VALUES(100000334, 'Ouvidoria: Crítica à atuação do Órgão');
INSERT INTO `tb_tipos_processo` VALUES(100000335, 'Ouvidoria: Denúncia contra a atuação do Órgão');
INSERT INTO `tb_tipos_processo` VALUES(100000336, 'Ouvidoria: Reclamação à atuação do Órgão');
INSERT INTO `tb_tipos_processo` VALUES(100000337, 'Ouvidoria: Agradecimento ao Órgão');
INSERT INTO `tb_tipos_processo` VALUES(100000338, 'Ouvidoria: Pedido de Informação');
INSERT INTO `tb_tipos_processo` VALUES(100000339, 'Gestão e Controle: Executar Auditoria Interna');
INSERT INTO `tb_tipos_processo` VALUES(100000341, 'Comunicação: Pedido de Apoio Institucional');
INSERT INTO `tb_tipos_processo` VALUES(100000342, 'Comunicação: Evento Institucional Público Externo');
INSERT INTO `tb_tipos_processo` VALUES(100000345, 'Comunicação: Evento Institucional Público Interno');
INSERT INTO `tb_tipos_processo` VALUES(100000351, 'Demanda Externa: Senador');
INSERT INTO `tb_tipos_processo` VALUES(100000352, 'Demanda Externa: Deputado Federal');
INSERT INTO `tb_tipos_processo` VALUES(100000353, 'Demanda Externa:: Deputado Estadual');
INSERT INTO `tb_tipos_processo` VALUES(100000354, 'Demanda Externa: Vereador/Câmara Municipal');
INSERT INTO `tb_tipos_processo` VALUES(100000355, 'Demanda Externa: Orgãos Governamentais Federais');
INSERT INTO `tb_tipos_processo` VALUES(100000356, 'Demanda Externa: Orgãos Governamentais Estaduais');
INSERT INTO `tb_tipos_processo` VALUES(100000357, 'Demanda Externa: Orgãos Governamentais Municipais');
INSERT INTO `tb_tipos_processo` VALUES(100000358, 'Demanda Externa: Outros Órgãos Públicos');
INSERT INTO `tb_tipos_processo` VALUES(100000361, 'Corregedoria: Investigação Preliminar');
INSERT INTO `tb_tipos_processo` VALUES(100000362, 'Corregedoria: Sindicância Punitiva');
INSERT INTO `tb_tipos_processo` VALUES(100000363, 'Corregedoria: Processo Administrativo Disciplinar');
INSERT INTO `tb_tipos_processo` VALUES(100000365, 'Gestão da Informação: Credenciamento de Segurança');
INSERT INTO `tb_tipos_processo` VALUES(100000366, 'Gestão da Informação: Normatização Interna');
INSERT INTO `tb_tipos_processo` VALUES(100000367, 'Gestão da Informação: Rol Anual de Informações Cla');
INSERT INTO `tb_tipos_processo` VALUES(100000368, 'Gestão da Informação: Avaliação/Destinação de Documentos');
INSERT INTO `tb_tipos_processo` VALUES(100000369, 'Gestão da Informação: Reconstituição Documental');
INSERT INTO `tb_tipos_processo` VALUES(100000372, 'Gestão de Projetos: Planejamento e Execução');
INSERT INTO `tb_tipos_processo` VALUES(100000373, 'Desenvolvimento Organizacional: Reestruturação e M');
INSERT INTO `tb_tipos_processo` VALUES(100000375, 'Auditoria em Demandas Pontuais');
INSERT INTO `tb_tipos_processo` VALUES(100000382, 'Demanda Externa: Cidadão (Pessoa Física)');
INSERT INTO `tb_tipos_processo` VALUES(100000383, 'Gestão de Contrato: Pagamento Direto a Terceiros');
INSERT INTO `tb_tipos_processo` VALUES(100000384, 'Gestão de TI: CITI');
INSERT INTO `tb_tipos_processo` VALUES(100000385, 'Demanda Externa: Judiciário');
INSERT INTO `tb_tipos_processo` VALUES(100000386, 'Demanda Externa: Ministério Público Estadual');
INSERT INTO `tb_tipos_processo` VALUES(100000387, 'Demanda Externa: Ministério Público Federal');
INSERT INTO `tb_tipos_processo` VALUES(100000388, 'Demanda Externa: Outras Entidades Privadas');
INSERT INTO `tb_tipos_processo` VALUES(100000389, 'Gestão da Informação: Controle de Malote');
INSERT INTO `tb_tipos_processo` VALUES(100000390, 'Suprimento de Fundos: Solicitação de Despesa');
INSERT INTO `tb_tipos_processo` VALUES(100000391, 'Material: Movimentação de Material Permanente');
INSERT INTO `tb_tipos_processo` VALUES(100000392, 'Pessoal: Saúde - Exclusão de Auxílio-Saúde');
INSERT INTO `tb_tipos_processo` VALUES(100000393, 'Pessoal: Saúde - Pagamento de Auxílio-Saúde');
INSERT INTO `tb_tipos_processo` VALUES(100000394, 'Pessoal: Saúde - Cadastro de Dependente Estudante ');
INSERT INTO `tb_tipos_processo` VALUES(100000395, 'Pessoal: Saúde - Auxílio-Saúde GEAP');
INSERT INTO `tb_tipos_processo` VALUES(100000396, 'Pessoal: Saúde - Atestado de Comparecimento');
INSERT INTO `tb_tipos_processo` VALUES(100000397, 'Pessoal: Saúde - Ressarcimento ao Erário');
INSERT INTO `tb_tipos_processo` VALUES(100000398, 'Pessoal: Saúde - Pagamento de Retroativo');
INSERT INTO `tb_tipos_processo` VALUES(100000399, 'Pessoal: Saúde e Qualidade de Vida no Trabalho');
INSERT INTO `tb_tipos_processo` VALUES(100000400, 'Pessoal: Ressarcimento ao Erário');
INSERT INTO `tb_tipos_processo` VALUES(100000401, 'Gestão de Contrato: Consulta à Procuradoria/Conjur');
INSERT INTO `tb_tipos_processo` VALUES(100000402, 'Gestão de Contrato: Acréscimo Contratual');
INSERT INTO `tb_tipos_processo` VALUES(100000403, 'Gestão de Contrato: Alterações Contratuais Conjunt');
INSERT INTO `tb_tipos_processo` VALUES(100000404, 'Gestão de Contrato');
INSERT INTO `tb_tipos_processo` VALUES(100000405, 'Pessoal: Abono Permanência - Revisão');
INSERT INTO `tb_tipos_processo` VALUES(100000406, 'RH: Aposentadoria - Revisão de Proventos');
INSERT INTO `tb_tipos_processo` VALUES(100000407, 'Pessoal: Capacitação');
INSERT INTO `tb_tipos_processo` VALUES(100000408, 'Licitação: Aplicação de Sanção decorrente de Proce');
INSERT INTO `tb_tipos_processo` VALUES(100000409, 'Gestão da Informação: Cadastro de Usuário Externo ');
INSERT INTO `tb_tipos_processo` VALUES(100000410, 'Pessoal: Saúde - Lançamento Mensal do Auxílio-Saúd');
INSERT INTO `tb_tipos_processo` VALUES(100000411, 'Segurança da Informação: Organização da Segurança da Informação');
INSERT INTO `tb_tipos_processo` VALUES(100000414, 'Pessoal: Curso no Exterior - ônus limitado');
INSERT INTO `tb_tipos_processo` VALUES(100000415, 'Pessoal: Curso no Exterior - sem ônus');
INSERT INTO `tb_tipos_processo` VALUES(100000417, 'Licitação: Consulta');
INSERT INTO `tb_tipos_processo` VALUES(100000418, 'Infraestrutura: Apoio de Engenharia Civil');
INSERT INTO `tb_tipos_processo` VALUES(100000419, 'Produção e Utilização de Documentos: Classificação e Arquivamento');
INSERT INTO `tb_tipos_processo` VALUES(100000420, 'Relações Internacionais: Composição de Delegação -');
INSERT INTO `tb_tipos_processo` VALUES(100000501, 'Cadastramento e Credenciamento de Fornecedores');
INSERT INTO `tb_tipos_processo` VALUES(100000502, 'Regularização de Imóveis');
INSERT INTO `tb_tipos_processo` VALUES(100000503, 'Acompanhamento de Portfólio');
INSERT INTO `tb_tipos_processo` VALUES(100000504, 'Alienação: Doação');
INSERT INTO `tb_tipos_processo` VALUES(100000505, 'Alienação: Retrocessão');
INSERT INTO `tb_tipos_processo` VALUES(100000506, 'Alienação: Reversão');
INSERT INTO `tb_tipos_processo` VALUES(100000507, 'Aquisição - Adjudicação ');
INSERT INTO `tb_tipos_processo` VALUES(100000508, 'Aquisição - Compra');
INSERT INTO `tb_tipos_processo` VALUES(100000509, 'Aquisição - Dação em pagamento');
INSERT INTO `tb_tipos_processo` VALUES(100000510, 'Aquisição - Desapropriação');
INSERT INTO `tb_tipos_processo` VALUES(100000511, 'Aquisição - Doação');
INSERT INTO `tb_tipos_processo` VALUES(100000512, 'Aquisição - Reversão');
INSERT INTO `tb_tipos_processo` VALUES(100000513, 'Aquisição - Usucapião');
INSERT INTO `tb_tipos_processo` VALUES(100000514, 'Aquisição de Veículos');
INSERT INTO `tb_tipos_processo` VALUES(100000515, 'Autorização de Obra em Imóveis Públicos');
INSERT INTO `tb_tipos_processo` VALUES(100000516, 'Autorização Provisória para Utilização de Imóveis ');
INSERT INTO `tb_tipos_processo` VALUES(100000517, 'Avaliação da Vantajosidade de Renovação Contratual');
INSERT INTO `tb_tipos_processo` VALUES(100000518, 'Cadastro de Material e Serviço: Classificação de D');
INSERT INTO `tb_tipos_processo` VALUES(100000519, 'Cadastro e Atualização de Imóveis');
INSERT INTO `tb_tipos_processo` VALUES(100000520, 'Cessão de Uso de Bens Móveis');
INSERT INTO `tb_tipos_processo` VALUES(100000521, 'Cessão de Uso de Veículos');
INSERT INTO `tb_tipos_processo` VALUES(100000522, 'CNPJ Administrativo');
INSERT INTO `tb_tipos_processo` VALUES(100000523, 'Consulta Disponibilidade de Imóveis');
INSERT INTO `tb_tipos_processo` VALUES(100000524, 'Dação em Pagamento');
INSERT INTO `tb_tipos_processo` VALUES(100000525, 'Demanda: Assessoramento à Defesa do Estado de Minas Gerais');
INSERT INTO `tb_tipos_processo` VALUES(100000526, 'Demanda Externa: Assessoramento Jurídico');
INSERT INTO `tb_tipos_processo` VALUES(100000527, 'Demanda Externa: Política e Regras de Compras Públ');
INSERT INTO `tb_tipos_processo` VALUES(100000528, 'Demanda Externa: Sistemas e Cadastros');
INSERT INTO `tb_tipos_processo` VALUES(100000529, 'Solicitação de Doação de Bens Móveis');
INSERT INTO `tb_tipos_processo` VALUES(100000530, 'Solicitação de Doação de Veículos');
INSERT INTO `tb_tipos_processo` VALUES(100000531, 'Assessoramento Técnico-Legislativo - Decreto');
INSERT INTO `tb_tipos_processo` VALUES(100000532, 'Assessoramento Técnico-Legislativo - Proposição de');
INSERT INTO `tb_tipos_processo` VALUES(100000533, 'Elaboração de Resolução');
INSERT INTO `tb_tipos_processo` VALUES(100000534, 'Elaboração de Resolução Conjunta');
INSERT INTO `tb_tipos_processo` VALUES(100000535, 'Empréstimo - Autorização de Uso do Imóvel');
INSERT INTO `tb_tipos_processo` VALUES(100000536, 'Empréstimo - Cessão de Uso do Imóvel');
INSERT INTO `tb_tipos_processo` VALUES(100000537, 'Empréstimo - Cessão de Uso, Permissão de Uso e Aut');
INSERT INTO `tb_tipos_processo` VALUES(100000538, 'Empréstimo - Desvinculação');
INSERT INTO `tb_tipos_processo` VALUES(100000539, 'Empréstimo - Permissão de uso do Imóvel');
INSERT INTO `tb_tipos_processo` VALUES(100000540, 'Empréstimo - Vinculação de Imóvel');
INSERT INTO `tb_tipos_processo` VALUES(100000541, 'Gestão de Contrato: Armazenamento de Documentos');
INSERT INTO `tb_tipos_processo` VALUES(100000542, 'Gestão de Contrato: Abastecimento');
INSERT INTO `tb_tipos_processo` VALUES(100000543, 'Gestao de Contrato: Aluguel de Veículos');
INSERT INTO `tb_tipos_processo` VALUES(100000544, 'Gestao de Contrato: Contratação de Menor de 18 anos');
INSERT INTO `tb_tipos_processo` VALUES(100000545, 'Gestão de Contrato: MGS - Terceirizados');
INSERT INTO `tb_tipos_processo` VALUES(100000546, 'Gestão de Contrato: Outros Serviços Postais');
INSERT INTO `tb_tipos_processo` VALUES(100000547, 'Serca/Malote');
INSERT INTO `tb_tipos_processo` VALUES(100000548, 'Serviço de Encomenda Expressa (Sedex) Nacional e I');
INSERT INTO `tb_tipos_processo` VALUES(100000549, 'Gestão de TI: Acesso a Sistemas Corporativos');
INSERT INTO `tb_tipos_processo` VALUES(100000550, 'Gestão de TI: Intervenções em Sistemas Corporativos');
INSERT INTO `tb_tipos_processo` VALUES(100000551, 'Gestão de TI: Solicitação de Administrador de Segurança');
INSERT INTO `tb_tipos_processo` VALUES(100000552, 'Identificação e Regularização de Débitos de Imóvei');
INSERT INTO `tb_tipos_processo` VALUES(100000553, 'Inventários: Bens Imóveis, veículos e bens semoven');
INSERT INTO `tb_tipos_processo` VALUES(100000554, 'Licitação de Bens Móveis: Pregão Eletrônico-Regist');
INSERT INTO `tb_tipos_processo` VALUES(100000555, 'Licitação: Contratação de Serviço');
INSERT INTO `tb_tipos_processo` VALUES(100000556, 'Licitação: Acordo Judicial - Dação em Pagamento');
INSERT INTO `tb_tipos_processo` VALUES(100000557, 'Licitação: Compra');
INSERT INTO `tb_tipos_processo` VALUES(100000558, 'Licitação: Dispensa');
INSERT INTO `tb_tipos_processo` VALUES(100000559, 'Licitação: Dispensa por Valor - Cotação Eletrônica');
INSERT INTO `tb_tipos_processo` VALUES(100000560, 'Licitação: Modalidades BIRD / BID');
INSERT INTO `tb_tipos_processo` VALUES(100000561, 'Licitação: Outras Contratações');
INSERT INTO `tb_tipos_processo` VALUES(100000562, 'Projetos de Parcerias Público-Privadas (PPP)');
INSERT INTO `tb_tipos_processo` VALUES(100000563, 'Licitação: Pregão Presencial-Registro de Preço');
INSERT INTO `tb_tipos_processo` VALUES(100000564, 'Licitação: Registro de Preços não Realizado no SIR');
INSERT INTO `tb_tipos_processo` VALUES(100000565, 'Licitação: Registro de Preços Realizado no SIRP');
INSERT INTO `tb_tipos_processo` VALUES(100000566, 'Locação de Veículos');
INSERT INTO `tb_tipos_processo` VALUES(100000567, 'Manifestação de Projeto de Lei');
INSERT INTO `tb_tipos_processo` VALUES(100000568, 'Orientação aos Órgãos e Entidades: Políticas e Reg');
INSERT INTO `tb_tipos_processo` VALUES(100000569, 'Orientação aos Órgãos e Entidades: Gestão da Frota');
INSERT INTO `tb_tipos_processo` VALUES(100000570, 'Permissão de Uso de Bens Móveis');
INSERT INTO `tb_tipos_processo` VALUES(100000571, 'Permissão de Uso de Veículos');
INSERT INTO `tb_tipos_processo` VALUES(100000572, 'Permuta');
INSERT INTO `tb_tipos_processo` VALUES(100000573, 'Processo Oriundo de Outros Setores do CSC');
INSERT INTO `tb_tipos_processo` VALUES(100000574, 'Serviços de Abastecimento');
INSERT INTO `tb_tipos_processo` VALUES(100000575, 'Serviços de Engenharia: Anuência de Rio');
INSERT INTO `tb_tipos_processo` VALUES(100000576, 'Serviços de Engenharia: Avaliação de Imóveis');
INSERT INTO `tb_tipos_processo` VALUES(100000577, 'Serviços de Engenharia: Manifestação do Estado em ');
INSERT INTO `tb_tipos_processo` VALUES(100000578, 'IEF -Serviços de Engenharia: Retificação de Área');
INSERT INTO `tb_tipos_processo` VALUES(100000579, 'Serviços de Engenharia: Vistoria de Imóveis');
INSERT INTO `tb_tipos_processo` VALUES(100000580, 'Serviços de Manutenção Veicular');
INSERT INTO `tb_tipos_processo` VALUES(100000581, 'Serviços de Transporte');
INSERT INTO `tb_tipos_processo` VALUES(100000582, 'Adjudicação');
INSERT INTO `tb_tipos_processo` VALUES(100000583, 'Processo Administrativo Punitivo de Fornecedores');
INSERT INTO `tb_tipos_processo` VALUES(100000584, 'Pessoal: Exoneração de Cargo de Provimento em Comissão ou Dispensa de Função Gratificada');
INSERT INTO `tb_tipos_processo` VALUES(100000585, 'Pessoal: Nomeação');
INSERT INTO `tb_tipos_processo` VALUES(100000586, 'Pessoal: Férias Prêmio - Alteração');
INSERT INTO `tb_tipos_processo` VALUES(100000587, 'Pessoal: Férias Prêmio - Interrupção');
INSERT INTO `tb_tipos_processo` VALUES(100000588, 'Pessoal: Férias Prêmio');
INSERT INTO `tb_tipos_processo` VALUES(100000589, 'Pessoal: Uso de Folga Compensativa');
INSERT INTO `tb_tipos_processo` VALUES(100000590, 'Consulta Jurídica');
INSERT INTO `tb_tipos_processo` VALUES(100000591, 'Atendimento Judiciário Socioeducativo: Atendimento');
INSERT INTO `tb_tipos_processo` VALUES(100000592, 'Apoio a Encaminhamento de Alta Complexidade');
INSERT INTO `tb_tipos_processo` VALUES(100000593, 'Gestão de TIC: Infraestrutura de Tecnologia da Informação e Comunicação');
INSERT INTO `tb_tipos_processo` VALUES(100000594, 'Obras Públicas: Construção e Conservação de Obras Públicas');
INSERT INTO `tb_tipos_processo` VALUES(100000595, 'Obras Públicas: Convênio');
INSERT INTO `tb_tipos_processo` VALUES(100000596, 'Processo Cofin');
INSERT INTO `tb_tipos_processo` VALUES(100000598, 'Fiscalização Operacional: Sede Água');
INSERT INTO `tb_tipos_processo` VALUES(100000599, 'Normatização: Operacional');
INSERT INTO `tb_tipos_processo` VALUES(100000600, 'Pessoal: Certificação de Nada Consta por motivo de');
INSERT INTO `tb_tipos_processo` VALUES(100000601, 'Informe de Eventos de Segurança das Unidades');
INSERT INTO `tb_tipos_processo` VALUES(100000602, 'Informações: Solicitações Gerais');
INSERT INTO `tb_tipos_processo` VALUES(100000603, 'Perícia Oficial: Exames Periciais');
INSERT INTO `tb_tipos_processo` VALUES(100000604, 'Perícia Oficial: Laudos Periciais');
INSERT INTO `tb_tipos_processo` VALUES(100000605, 'Perícia Oficial: Procedimentos Operacionais');
INSERT INTO `tb_tipos_processo` VALUES(100000606, 'Perícia Oficial: Assistente Técnico');
INSERT INTO `tb_tipos_processo` VALUES(100000607, 'Perícia Oficial: Articulação Interinstitucional');
INSERT INTO `tb_tipos_processo` VALUES(100000608, 'Perícia Oficial: Projetos');
INSERT INTO `tb_tipos_processo` VALUES(100000609, 'Documentos Digitalizados na Ilha Central de Digitalização Cidade Administrativa');
INSERT INTO `tb_tipos_processo` VALUES(100000611, 'Manutenção de Equipamentos');
INSERT INTO `tb_tipos_processo` VALUES(100000612, 'Prevenção à Criminalidade: Mediação de Conflitos');
INSERT INTO `tb_tipos_processo` VALUES(100000613, 'Prevenção à Criminalidade: Estudos e Pesquisas');
INSERT INTO `tb_tipos_processo` VALUES(100000614, 'Prevenção à Criminalidade: Supervisão dos Gestores');
INSERT INTO `tb_tipos_processo` VALUES(100000615, 'Desenvolvimento Social: Proteção da Mulher');
INSERT INTO `tb_tipos_processo` VALUES(100000616, 'Desenvolvimento Social: Inclusão Social e Promoção');
INSERT INTO `tb_tipos_processo` VALUES(100000617, 'Conselho Deliberativo de Desenvolvimento da Região');
INSERT INTO `tb_tipos_processo` VALUES(100000618, 'Gestão de TIC: Sistemas de Informações e Aplicações');
INSERT INTO `tb_tipos_processo` VALUES(100000619, 'Orientações aos Órgãos e Entidades Emitidas por Unidades Centrais');
INSERT INTO `tb_tipos_processo` VALUES(100000620, 'Gestão de TIC: Análise das Demandas de Tecnologia ');
INSERT INTO `tb_tipos_processo` VALUES(100000621, 'Gestão Prodemge');
INSERT INTO `tb_tipos_processo` VALUES(100000622, 'CRV: Segunda Via');
INSERT INTO `tb_tipos_processo` VALUES(100000623, 'CRLV');
INSERT INTO `tb_tipos_processo` VALUES(100000624, 'CNH: Alteração de dados');
INSERT INTO `tb_tipos_processo` VALUES(100000625, 'Veículos: Baixa de Gravame');
INSERT INTO `tb_tipos_processo` VALUES(100000626, 'Veículos: Inclusão e Baixa de Comunicação de Venda');
INSERT INTO `tb_tipos_processo` VALUES(100000627, 'Veículos: Retorno de propriedade');
INSERT INTO `tb_tipos_processo` VALUES(100000628, 'Veículos: Baixa Definitiva');
INSERT INTO `tb_tipos_processo` VALUES(100000629, 'Veículos: Destituição de Propriedade');
INSERT INTO `tb_tipos_processo` VALUES(100000630, 'Veículos: Transfêrencia de Jurisdição');
INSERT INTO `tb_tipos_processo` VALUES(100000631, 'CNH: Baixa e transferência de Pontuação');
INSERT INTO `tb_tipos_processo` VALUES(100000632, 'CNH: Baixa e suspensão de multa');
INSERT INTO `tb_tipos_processo` VALUES(100000633, 'CNH: Suspensão do direito de dirigir-novos exames');
INSERT INTO `tb_tipos_processo` VALUES(100000634, 'CNH: PAP e PAI');
INSERT INTO `tb_tipos_processo` VALUES(100000635, 'Veículos: Baixa de Débito');
INSERT INTO `tb_tipos_processo` VALUES(100000636, 'Credenciamento de Fábrica de Placas');
INSERT INTO `tb_tipos_processo` VALUES(100000637, 'Credenciamento de CFC');
INSERT INTO `tb_tipos_processo` VALUES(100000638, 'Veículos: Bloqueio de Veículo');
INSERT INTO `tb_tipos_processo` VALUES(100000639, 'Veículos: Liberação de Veículos');
INSERT INTO `tb_tipos_processo` VALUES(100000640, 'CNH: Pesquisa Condutor');
INSERT INTO `tb_tipos_processo` VALUES(100000641, 'Veículos: Pesquisa Veículo');
INSERT INTO `tb_tipos_processo` VALUES(100000642, 'Veículos: Averbação');
INSERT INTO `tb_tipos_processo` VALUES(100000643, 'CNH: transferencia de CNH - Outro Estado');
INSERT INTO `tb_tipos_processo` VALUES(100000644, 'Veículos: Leilão');
INSERT INTO `tb_tipos_processo` VALUES(100000645, 'Gestão de Atas de Registro de Preços');
INSERT INTO `tb_tipos_processo` VALUES(100000646, 'Avaliação de Mercado');
INSERT INTO `tb_tipos_processo` VALUES(100000647, 'Comunicação: Interna');
INSERT INTO `tb_tipos_processo` VALUES(100000649, 'Pessoal: Acréscimo de Efetivo');
INSERT INTO `tb_tipos_processo` VALUES(100000650, 'Ações Judiciais: Mandado de Prisão');
INSERT INTO `tb_tipos_processo` VALUES(100000651, 'Ações Policiais: Folha de Antecedentes Criminais');
INSERT INTO `tb_tipos_processo` VALUES(100000652, 'Ações Policiais: Carta Precatória');
INSERT INTO `tb_tipos_processo` VALUES(100000653, 'Ações Policiais: Carteira de Identidade');
INSERT INTO `tb_tipos_processo` VALUES(100000654, 'Ações Policias: Relação de Óbitos');
INSERT INTO `tb_tipos_processo` VALUES(100000655, 'Ações Policiais:  Plantão Regionalizado');
INSERT INTO `tb_tipos_processo` VALUES(100000656, 'Organização e Funcionamento do Órgão: Normas, Regulamentos, Diretrizes ou Procedimentos');
INSERT INTO `tb_tipos_processo` VALUES(100000657, 'Administração Geral: Instalação de Unidade Policia');
INSERT INTO `tb_tipos_processo` VALUES(100000658, 'Gestão de Convênios - Entre unidades Governamentais e não Governamentais');
INSERT INTO `tb_tipos_processo` VALUES(100000659, 'Convênios/Ajustes: Termo de Colaboração');
INSERT INTO `tb_tipos_processo` VALUES(100000660, 'Gestão de Imóveis: Energia');
INSERT INTO `tb_tipos_processo` VALUES(100000661, 'Cadastramento de Usuários Externos');
INSERT INTO `tb_tipos_processo` VALUES(100000662, 'Licitação: Leilão - Credenciamento de Arrematantes');
INSERT INTO `tb_tipos_processo` VALUES(100000663, 'Indenização');
INSERT INTO `tb_tipos_processo` VALUES(100000664, 'Licitação: Leilão - Processo Administrativo Puniti');
INSERT INTO `tb_tipos_processo` VALUES(100000665, 'Patrimônio: Desaparecimento ou Avaria de Bens');
INSERT INTO `tb_tipos_processo` VALUES(100000666, 'Solicitação de Doação de Veículos Usados - Pátio G');
INSERT INTO `tb_tipos_processo` VALUES(100000667, 'Solicitação de Doação Bens Usados  (exceto veículo');
INSERT INTO `tb_tipos_processo` VALUES(100000668, 'Demanda Externa: Tribunal de Contas do Estado');
INSERT INTO `tb_tipos_processo` VALUES(100000669, 'Conselho de Administração de Pessoal (CAP)');
INSERT INTO `tb_tipos_processo` VALUES(100000670, 'Viagem a Serviço: Com ônus para a instituição');
INSERT INTO `tb_tipos_processo` VALUES(100000671, 'Viagem: Prestação de Contas de Adiantamento');
INSERT INTO `tb_tipos_processo` VALUES(100000672, 'Governo Aberto, Transparência e Controle Social');
INSERT INTO `tb_tipos_processo` VALUES(100000673, 'Pessoal: Férias Prêmio - Concessão');
INSERT INTO `tb_tipos_processo` VALUES(100000674, 'Pessoal: Requerimento para ausentar-se do País');
INSERT INTO `tb_tipos_processo` VALUES(100000675, 'Tomada de Contas Especial');
INSERT INTO `tb_tipos_processo` VALUES(100000676, 'Perícia Oficial: Transferência de Material');
INSERT INTO `tb_tipos_processo` VALUES(100000678, 'Informações: Operacionais');
INSERT INTO `tb_tipos_processo` VALUES(100000679, 'Informações: Econômico-financeiras');
INSERT INTO `tb_tipos_processo` VALUES(100000680, 'Homologação de Documentos: Operacional');
INSERT INTO `tb_tipos_processo` VALUES(100000681, 'Fiscalização Operacional: Sede Esgoto');
INSERT INTO `tb_tipos_processo` VALUES(100000682, 'Fiscalização Operacional: Distrito Água');
INSERT INTO `tb_tipos_processo` VALUES(100000683, 'Fiscalização Operacional: Distrito Esgoto');
INSERT INTO `tb_tipos_processo` VALUES(100000684, 'SEPLAG - Celebração de Contratos Mais Asfalto - Mu');
INSERT INTO `tb_tipos_processo` VALUES(100000685, 'Pessoal: Pasta Funcional Digital');
INSERT INTO `tb_tipos_processo` VALUES(100000686, 'Funcionamento Escolar: Documentação');
INSERT INTO `tb_tipos_processo` VALUES(100000687, 'Processo Judicial');
INSERT INTO `tb_tipos_processo` VALUES(100000688, 'RH: Posicionamento / Reposicionamento');
INSERT INTO `tb_tipos_processo` VALUES(100000689, 'RH: Contratação para Atender a Necessidade Temporária de Excepcional Interesse Público');
INSERT INTO `tb_tipos_processo` VALUES(100000690, 'Pessoal: Ajustamento Funcional');
INSERT INTO `tb_tipos_processo` VALUES(100000691, 'Pessoal: Agendamento de Junta Médica');
INSERT INTO `tb_tipos_processo` VALUES(100000692, 'Pessoal: Perfil Profissiográfico Previdenciário');
INSERT INTO `tb_tipos_processo` VALUES(100000693, 'Pessoal: Acolhimento Psicológico ao Servidor');
INSERT INTO `tb_tipos_processo` VALUES(100000694, 'Assistência Médica IPSEMG');
INSERT INTO `tb_tipos_processo` VALUES(100000695, 'Pessoal: Alteração de Dados Funcionais');
INSERT INTO `tb_tipos_processo` VALUES(100000696, 'Pessoal: Cadastro de Dependentes do Servidor');
INSERT INTO `tb_tipos_processo` VALUES(100000697, 'Despesas dos Exercícios Anteriores (DEA)');
INSERT INTO `tb_tipos_processo` VALUES(100000698, 'Gestão de Contrato: Encerramento');
INSERT INTO `tb_tipos_processo` VALUES(100000699, 'Finanças: Execução Financeira da Despesa');
INSERT INTO `tb_tipos_processo` VALUES(100000700, 'Documentos Digitalizados no Protocolo/Apoio Administrativo');
INSERT INTO `tb_tipos_processo` VALUES(100000701, 'Pessoal: Pensão Especial (SEF)');
INSERT INTO `tb_tipos_processo` VALUES(100000702, 'Pessoal: Reenquadramento');
INSERT INTO `tb_tipos_processo` VALUES(100000703, 'Pessoal: Licença À Gestante');
INSERT INTO `tb_tipos_processo` VALUES(100000704, 'Pessoal: Jeton');
INSERT INTO `tb_tipos_processo` VALUES(100000705, 'Pessoal: Licença Para Acompanhar Cônjuge');
INSERT INTO `tb_tipos_processo` VALUES(100000706, 'Pessoal: Licença Para Tratamento de Saúde');
INSERT INTO `tb_tipos_processo` VALUES(100000707, 'Pessoal: Quinquênio');
INSERT INTO `tb_tipos_processo` VALUES(100000708, 'Pessoal: Acúmulo de Cargos');
INSERT INTO `tb_tipos_processo` VALUES(100000709, 'RH: Alteração de Carga Horária');
INSERT INTO `tb_tipos_processo` VALUES(100000710, 'Pessoal: Previdência Complementar');
INSERT INTO `tb_tipos_processo` VALUES(100000711, 'Pessoal: Participação em Evento, Treinamento, Curso ou Missão');
INSERT INTO `tb_tipos_processo` VALUES(100000712, 'RH: Título Declaratório de Apostilamento');
INSERT INTO `tb_tipos_processo` VALUES(100000713, 'Pessoal: Elaboração de Políticas de Recursos Humanos');
INSERT INTO `tb_tipos_processo` VALUES(100000714, 'Pessoal: Nomeação/Exoneração Judicial');
INSERT INTO `tb_tipos_processo` VALUES(100000715, 'Cumprimento de Decisão Judicial');
INSERT INTO `tb_tipos_processo` VALUES(100000716, 'Pessoal: Rescisão de Contrato (Lei 18.185/2009)');
INSERT INTO `tb_tipos_processo` VALUES(100000717, 'Pessoal: Promoção por Escolaridade Adicional');
INSERT INTO `tb_tipos_processo` VALUES(100000718, 'Aquisição: Contratações de Material Permanente e de Consumo (Pronto Pagamento)');
INSERT INTO `tb_tipos_processo` VALUES(100000719, 'Finanças: Convalidação de Documentos da Execução O');
INSERT INTO `tb_tipos_processo` VALUES(100000720, 'Conselho Curador');
INSERT INTO `tb_tipos_processo` VALUES(100000723, 'IDENE -  Perfuração de Poços Tubulares para Prefei');
INSERT INTO `tb_tipos_processo` VALUES(100000724, 'Modernização Institucional: Celebração de Termos de Parceria');
INSERT INTO `tb_tipos_processo` VALUES(100000725, 'Hemoterapia');
INSERT INTO `tb_tipos_processo` VALUES(100000726, 'Hematologia');
INSERT INTO `tb_tipos_processo` VALUES(100000727, 'Células e Tecidos Biológicos');
INSERT INTO `tb_tipos_processo` VALUES(100000728, 'Doação de Bens Móveis');
INSERT INTO `tb_tipos_processo` VALUES(100000729, 'Comissões Intergestores (CIB/CIRA/CIR)');
INSERT INTO `tb_tipos_processo` VALUES(100000730, 'Incentivo a Projetos Esportivos por Meio de Renúnc');
INSERT INTO `tb_tipos_processo` VALUES(100000731, 'ICMS Esportivo');
INSERT INTO `tb_tipos_processo` VALUES(100000732, 'Índice Mineiro de Desenvolvimento Esportivo');
INSERT INTO `tb_tipos_processo` VALUES(100000733, 'Corregedoria: Processo Administrativo de Responsab');
INSERT INTO `tb_tipos_processo` VALUES(100000734, 'IEF - Serviço de Engenharia: Anuência INCRA');
INSERT INTO `tb_tipos_processo` VALUES(100000735, 'RH: Reabilitação');
INSERT INTO `tb_tipos_processo` VALUES(100000736, 'Finanças: Ateste de Notas Fiscais');
INSERT INTO `tb_tipos_processo` VALUES(100000737, 'Requerimento de Contribuinte');
INSERT INTO `tb_tipos_processo` VALUES(100000738, 'Pessoal: Inclusão/Alteração de Dados no SISAP');
INSERT INTO `tb_tipos_processo` VALUES(100000739, 'Pessoal: Afastamento Preliminar à Aposentadoria');
INSERT INTO `tb_tipos_processo` VALUES(100000740, 'Gestão de Contrato: Apostilamento');
INSERT INTO `tb_tipos_processo` VALUES(100000741, 'Pedido de Informação');
INSERT INTO `tb_tipos_processo` VALUES(100000742, 'Segurança Pública: Estatística e análise criminal');
INSERT INTO `tb_tipos_processo` VALUES(100000743, 'Segurança Pública: Classificação e análise datilos');
INSERT INTO `tb_tipos_processo` VALUES(100000744, 'Segurança Pública: Identificação Externa');
INSERT INTO `tb_tipos_processo` VALUES(100000745, 'Segurança Pública: Emissão de Carteiras');
INSERT INTO `tb_tipos_processo` VALUES(100000746, 'Contratação de Terceiros para Serviços de Assistên');
INSERT INTO `tb_tipos_processo` VALUES(100000747, 'Segurança Pública: Óbitos');
INSERT INTO `tb_tipos_processo` VALUES(100000748, 'Publicação e Processamento de Atos Normativos e Resoluções');
INSERT INTO `tb_tipos_processo` VALUES(100000749, 'Segurança Pública: Pesquisas e confronto datiloscó');
INSERT INTO `tb_tipos_processo` VALUES(100000750, 'RH: Processo Administrativo Resolução - SEPLAG n° 37/2005 (inclusive Cobrança de débito)');
INSERT INTO `tb_tipos_processo` VALUES(100000751, 'Pessoal: Orientações sobre Direitos e Benefícios dos Servidores');
INSERT INTO `tb_tipos_processo` VALUES(100000752, 'RH: Atribuição ou Dispensa de Gratificação Temporária Estratégica (GTED)');
INSERT INTO `tb_tipos_processo` VALUES(100000753, 'Pessoal: Atribuição ou Dispensa de Chefia de Órgão, Entidade ou Unidade Administrativa');
INSERT INTO `tb_tipos_processo` VALUES(100000754, 'Solicitação de Adiantamento em Viagens');
INSERT INTO `tb_tipos_processo` VALUES(100000755, 'RH: Licença p/ Afast. Remunerado de Servidor Púb. Candidato a Eleição Municipal, Estadual e Federal');
INSERT INTO `tb_tipos_processo` VALUES(100000756, 'RH: Afastamento por Motivo de Casamento');
INSERT INTO `tb_tipos_processo` VALUES(100000757, 'SESP - Internação Provisória');
INSERT INTO `tb_tipos_processo` VALUES(100000758, 'Pessoal: Vencimentos Deixados');
INSERT INTO `tb_tipos_processo` VALUES(100000759, 'RH: Afastamento por Motivo de Luto');
INSERT INTO `tb_tipos_processo` VALUES(100000760, 'RH: Licença Paternidade');
INSERT INTO `tb_tipos_processo` VALUES(100000761, 'RH: Licença à Gestante');
INSERT INTO `tb_tipos_processo` VALUES(100000762, 'Pessoal: Adicional Trintenário');
INSERT INTO `tb_tipos_processo` VALUES(100000763, 'RH: Ordem de Pagamento Especial (OPE)');
INSERT INTO `tb_tipos_processo` VALUES(100000768, 'Patrimônio: Locação (imobiliário)');
INSERT INTO `tb_tipos_processo` VALUES(100000777, 'RH: Concurso Público - Homologação');
INSERT INTO `tb_tipos_processo` VALUES(100000778, 'RH: Concurso Público - Solicitação de Realização');
INSERT INTO `tb_tipos_processo` VALUES(100000779, 'RH: Concurso Público - Autorização de Nomeação de ');
INSERT INTO `tb_tipos_processo` VALUES(100000780, 'RH: Concurso Público - Autorização de Realização');
INSERT INTO `tb_tipos_processo` VALUES(100000781, 'RH: Nomeação - Cargo Efetivo');
INSERT INTO `tb_tipos_processo` VALUES(100000782, 'RH: Prêmios, Concessões de Medalhas, Diplomas de Honra ao Mérito, Elogios');
INSERT INTO `tb_tipos_processo` VALUES(100000783, 'RH: Pasta Funcional Física - Migração de Passivo');
INSERT INTO `tb_tipos_processo` VALUES(100000784, 'RH: Processamento de Processo Administrativo Disciplinar (PAD) concluído');
INSERT INTO `tb_tipos_processo` VALUES(100000787, 'RH: Convênio não Oneroso para Concessão de Benefíc');
INSERT INTO `tb_tipos_processo` VALUES(100000788, 'RH: Concessão de Férias Prêmio');
INSERT INTO `tb_tipos_processo` VALUES(100000789, 'RH: Gozo de Férias Prêmio');
INSERT INTO `tb_tipos_processo` VALUES(100000800, 'RH: Remoção a Pedido por Interesse Pessoal');
INSERT INTO `tb_tipos_processo` VALUES(100000801, 'RH: Remoção a Pedido por Motivo de Saúde');
INSERT INTO `tb_tipos_processo` VALUES(100000802, 'RH: Remoção Ex-Officio');
INSERT INTO `tb_tipos_processo` VALUES(100000803, 'RH: Remoção a Pedido por Permuta');
INSERT INTO `tb_tipos_processo` VALUES(100000804, 'RH: Afastamento do trabalho para estudo ou aperfeiçoamento profissional');
INSERT INTO `tb_tipos_processo` VALUES(100000805, 'IDENE - Doação de Caixas D\'Água Metálica');
INSERT INTO `tb_tipos_processo` VALUES(100000806, 'RH: Repositório de bases de conhecimento');
INSERT INTO `tb_tipos_processo` VALUES(100000807, 'IPEM - Credenciamento de oficina');
INSERT INTO `tb_tipos_processo` VALUES(100000808, 'Controle das Atividades Contábeis: Relatório de Co');
INSERT INTO `tb_tipos_processo` VALUES(100000809, 'RH: Frequência');
INSERT INTO `tb_tipos_processo` VALUES(100000810, 'Pessoal: Gratificação Complementar de Produtividade (GCP)');
INSERT INTO `tb_tipos_processo` VALUES(100000811, 'Orçamento: Reestabelecimento de Restos a Pagar');
INSERT INTO `tb_tipos_processo` VALUES(100000812, 'RH: Concurso Público - Instituição de comissão esp');
INSERT INTO `tb_tipos_processo` VALUES(100000813, 'Orçamento: Levantamento de Informações (PPAG-LOA)');
INSERT INTO `tb_tipos_processo` VALUES(100000814, 'RH: Requerimento alteração de endereço');
INSERT INTO `tb_tipos_processo` VALUES(100000816, 'RH: Reunião de instância colegiada');
INSERT INTO `tb_tipos_processo` VALUES(100000817, 'RH: Emissão de Certidões e Declarações');
INSERT INTO `tb_tipos_processo` VALUES(100000818, 'RH: Requerimento Alteração de Nome');
INSERT INTO `tb_tipos_processo` VALUES(100000819, 'RH: Alteração de conta bancária para pagamento do ');
INSERT INTO `tb_tipos_processo` VALUES(100000820, 'RH: Requerimento de certidões de pag. pessoal');
INSERT INTO `tb_tipos_processo` VALUES(100000821, 'RH: Estágio');
INSERT INTO `tb_tipos_processo` VALUES(100000822, 'RH: Estagiário');
INSERT INTO `tb_tipos_processo` VALUES(100000823, 'Solicitação de Medicamento CEAF');
INSERT INTO `tb_tipos_processo` VALUES(100000824, 'RH: Posse e Exercício - Cargo efetivo');
INSERT INTO `tb_tipos_processo` VALUES(100000825, 'RH: Recadastramento');
INSERT INTO `tb_tipos_processo` VALUES(100000826, 'RH: Requerimento de Férias Regulamentares');
INSERT INTO `tb_tipos_processo` VALUES(100000827, 'RH: Req. Alteração de Férias Regulamentares');
INSERT INTO `tb_tipos_processo` VALUES(100000828, 'RH: Concessão de Quinquênio');
INSERT INTO `tb_tipos_processo` VALUES(100000829, 'RH: Licença para Acompanhar Cônjuge (LAC)');
INSERT INTO `tb_tipos_processo` VALUES(100000830, 'RH: Aposentadoria - Prévia');
INSERT INTO `tb_tipos_processo` VALUES(100000831, 'RH: Concessão de Adicional por Tempo de Serviço');
INSERT INTO `tb_tipos_processo` VALUES(100000832, 'RH: Estágio Curricular Obrigatório do Curso Superior de Administração Pública da FJP');
INSERT INTO `tb_tipos_processo` VALUES(100000833, 'Licitação: Procedimento das Estatais (Lei 13.303/2');
INSERT INTO `tb_tipos_processo` VALUES(100000834, 'Licitação: Credenciamento');
INSERT INTO `tb_tipos_processo` VALUES(100000835, 'Licitação: COTEP');
INSERT INTO `tb_tipos_processo` VALUES(100000836, 'Recuperação de Crédito');
INSERT INTO `tb_tipos_processo` VALUES(100000837, 'SEMAD Protocolo SUPRAM - TM');
INSERT INTO `tb_tipos_processo` VALUES(100000838, 'SEMAD Protocolo SUPRAM - SUL');
INSERT INTO `tb_tipos_processo` VALUES(100000839, 'SEMAD Protocolo SUPRAM - CENTRAL');
INSERT INTO `tb_tipos_processo` VALUES(100000840, 'Assessoramento Técnico-Legislativo - Projeto de Le');
INSERT INTO `tb_tipos_processo` VALUES(100000841, 'Assessoramento Técnico-Legislativo - Mensagem do G');
INSERT INTO `tb_tipos_processo` VALUES(100000842, 'Segurança Alimentar e Apoio à Agricultura Familiar');
INSERT INTO `tb_tipos_processo` VALUES(100000843, 'Desenvolvimento Rural Sustentável: Cadeias Produti');
INSERT INTO `tb_tipos_processo` VALUES(100000844, 'Desenvolvimento Rural Sustentável: Cadeias Produti');
INSERT INTO `tb_tipos_processo` VALUES(100000845, 'Desenvolvimento Rural Sustentável: Engenharia e Lo');
INSERT INTO `tb_tipos_processo` VALUES(100000846, 'Desenvolvimento Rural Sustentável: Agricultura Irr');
INSERT INTO `tb_tipos_processo` VALUES(100000847, 'Pedidos, Oferecimentos e Informações Diversas: Deputado Estadual');
INSERT INTO `tb_tipos_processo` VALUES(100000848, 'RH: Posse - Cargo/Função/Gratificação');
INSERT INTO `tb_tipos_processo` VALUES(100000849, 'RH: Conversão de Férias Prêmio em Espécie');
INSERT INTO `tb_tipos_processo` VALUES(100000850, 'SETOP: Concessão Rodoviária');
INSERT INTO `tb_tipos_processo` VALUES(100000851, 'Desenvolvimento do Setor Produtivo: Atração de Inv');
INSERT INTO `tb_tipos_processo` VALUES(100000852, 'Pedidos, Oferecimentos e Informações Diversas: Judiciário');
INSERT INTO `tb_tipos_processo` VALUES(100000853, 'Pedidos, Oferecimentos e Informações Diversas: Cidadão (Pessoa Física)');
INSERT INTO `tb_tipos_processo` VALUES(100000854, 'Pedidos, Oferecimentos e Informações Diversas: Entidades Privadas');
INSERT INTO `tb_tipos_processo` VALUES(100000855, 'Pedidos, Oferecimentos e Informações Diversas: Deputado Federal');
INSERT INTO `tb_tipos_processo` VALUES(100000856, 'Pedidos, Oferecimentos e Informações Diversas: Ministério Público Estadual');
INSERT INTO `tb_tipos_processo` VALUES(100000857, 'Pedidos, Oferecimentos e Informações Diversas: Órgãos Governamentais Estaduais');
INSERT INTO `tb_tipos_processo` VALUES(100000858, 'Pedidos, Oferecimentos e Informações Diversas: Órgãos Governamentais Federais');
INSERT INTO `tb_tipos_processo` VALUES(100000859, 'Pedidos, Oferecimentos e Informações Diversas: Órgãos Governamentais Municipais');
INSERT INTO `tb_tipos_processo` VALUES(100000860, 'Pedidos, Oferecimentos e Informações Diversas: Sen');
INSERT INTO `tb_tipos_processo` VALUES(100000861, 'Pedidos, Oferecimentos e Informações Diversas: Tribunal de Contas do Estado');
INSERT INTO `tb_tipos_processo` VALUES(100000862, 'Pedidos, Oferecimentos e Informações Diversas:  Vereador/Câmara Municipal');
INSERT INTO `tb_tipos_processo` VALUES(100000863, 'Pedidos, Oferecimentos e Informações Diversas');
INSERT INTO `tb_tipos_processo` VALUES(100000864, 'Pedidos, Oferecimentos e Informações Diversas:  Ministério Público Federal');
INSERT INTO `tb_tipos_processo` VALUES(100000865, 'RH: Alteração de dados IRPF');
INSERT INTO `tb_tipos_processo` VALUES(100000866, 'RH: Pensão Libertas');
INSERT INTO `tb_tipos_processo` VALUES(100000867, 'Vigilância Sanitária');
INSERT INTO `tb_tipos_processo` VALUES(100000868, 'RH: Cadastro de Vinculado');
INSERT INTO `tb_tipos_processo` VALUES(100000869, 'Contratações de Serviços: Aquisição de Serviços');
INSERT INTO `tb_tipos_processo` VALUES(100000870, 'VEÍCULOS: Multas');
INSERT INTO `tb_tipos_processo` VALUES(100000871, 'RH: Efetivação');
INSERT INTO `tb_tipos_processo` VALUES(100000872, 'RH: Afastamento do trabalho para estudo ou aperfeiçoamento profissional - PRORROGAÇÃO');
INSERT INTO `tb_tipos_processo` VALUES(100000873, 'Licenciamento Ambiental Simplificado');
INSERT INTO `tb_tipos_processo` VALUES(100000874, 'Implementação de Políticas de Ciência e Tecnologia');
INSERT INTO `tb_tipos_processo` VALUES(100000875, 'Produção de Tecnologias de Correição: Desenvolvime');
INSERT INTO `tb_tipos_processo` VALUES(100000876, 'Produção de Tecnologias de Correição: Divulgação');
INSERT INTO `tb_tipos_processo` VALUES(100000877, 'Ética');
INSERT INTO `tb_tipos_processo` VALUES(100000878, 'Recebimento e Análise de Denúncia de Correição');
INSERT INTO `tb_tipos_processo` VALUES(100000879, 'Processamento de Dados Estatísticos de Correição A');
INSERT INTO `tb_tipos_processo` VALUES(100000880, 'Criação e Revisão de Manuais. Técnicas e Rotinas d');
INSERT INTO `tb_tipos_processo` VALUES(100000881, 'Catálogo de Materiais');
INSERT INTO `tb_tipos_processo` VALUES(100000882, 'Cadastro de Fornecedores Impedidos de Licitar e Co');
INSERT INTO `tb_tipos_processo` VALUES(100000883, 'Patrimônio: Transferência Direta Bens Móveis (Incl');
INSERT INTO `tb_tipos_processo` VALUES(100000884, 'Reembolso de Despesas: Outros Reembolsos');
INSERT INTO `tb_tipos_processo` VALUES(100000885, 'RH: Convocação para Retorno de Férias');
INSERT INTO `tb_tipos_processo` VALUES(100000886, 'Acessos de Usuários/Servidores nos Portais Institucionais');
INSERT INTO `tb_tipos_processo` VALUES(100000887, 'RH: Substituição Temporária');
INSERT INTO `tb_tipos_processo` VALUES(100000888, 'RH: Afastamento Voluntário Incentivado (AVI)');
INSERT INTO `tb_tipos_processo` VALUES(100000889, 'RH: Exoneração de Cargo Efetivo ou Dispensa de Fun');
INSERT INTO `tb_tipos_processo` VALUES(100000890, 'Controle das Informações da Inteligência Prisional');
INSERT INTO `tb_tipos_processo` VALUES(100000891, 'Inteligência Prisional: Investigação Social');
INSERT INTO `tb_tipos_processo` VALUES(100000892, 'Assistência ao Preso: Documentos técnicos');
INSERT INTO `tb_tipos_processo` VALUES(100000893, 'Assistência ao Preso: Documentos financeiros');
INSERT INTO `tb_tipos_processo` VALUES(100000894, 'Cogestão Prisional: Documentos técnicos');
INSERT INTO `tb_tipos_processo` VALUES(100000895, 'Cogestão Prisional: Documentos financeiros');
INSERT INTO `tb_tipos_processo` VALUES(100000896, 'Administração Prisional: Indicadores de Desempenho');
INSERT INTO `tb_tipos_processo` VALUES(100000897, 'Administração Prisional: Gestão de Projetos');
INSERT INTO `tb_tipos_processo` VALUES(100000898, 'Administração Prisional: Comissão Técnica de Classificação (CTC)');
INSERT INTO `tb_tipos_processo` VALUES(100000899, 'Administração Prisional: Supervisão das Comissões Técnicas de Classificação(CTC)');
INSERT INTO `tb_tipos_processo` VALUES(100000900, 'Classificação Técnica do Preso');
INSERT INTO `tb_tipos_processo` VALUES(100000901, 'Projetos para Atendimento à Saúde do Preso');
INSERT INTO `tb_tipos_processo` VALUES(100000902, 'Prontuário Médico do Preso');
INSERT INTO `tb_tipos_processo` VALUES(100000903, 'Saúde do Preso: Controle de Estoque. Distribuição ');
INSERT INTO `tb_tipos_processo` VALUES(100000904, 'Saúde do Preso: Mapa de Medicamentos');
INSERT INTO `tb_tipos_processo` VALUES(100000905, 'Saúde do Preso: Controle de Estoque. Distribuição ');
INSERT INTO `tb_tipos_processo` VALUES(100000906, 'Saúde do preso: Controle Epidemiológico');
INSERT INTO `tb_tipos_processo` VALUES(100000907, 'Atendimento. Cadastramento de Familiares e Companh');
INSERT INTO `tb_tipos_processo` VALUES(100000908, 'Gestão dos Estabelecimentos de Saúde das Unidades ');
INSERT INTO `tb_tipos_processo` VALUES(100000909, 'Saúde do preso: Cadastro das Equipes de Saúde');
INSERT INTO `tb_tipos_processo` VALUES(100000910, 'Transferência de Preso para Tratamento de Saúde');
INSERT INTO `tb_tipos_processo` VALUES(100000911, 'Atendimento Jurídico. Apoio ao Preso');
INSERT INTO `tb_tipos_processo` VALUES(100000912, 'Administração Prisional: Relatório Diário de Ocorr');
INSERT INTO `tb_tipos_processo` VALUES(100000913, 'Administração Prisional: Escolta Externa de Alta P');
INSERT INTO `tb_tipos_processo` VALUES(100000914, 'Intervenções Especializadas. Escoltas de Alta Peri');
INSERT INTO `tb_tipos_processo` VALUES(100000915, 'Intervenção Tática e/ou Administrativa na Unidade ');
INSERT INTO `tb_tipos_processo` VALUES(100000916, 'Administração Prisional: Escolta Externa');
INSERT INTO `tb_tipos_processo` VALUES(100000917, 'Administração Prisional: Prevenção de Segurança Ex');
INSERT INTO `tb_tipos_processo` VALUES(100000918, 'Administração Prisional: Intervenção de Emergência');
INSERT INTO `tb_tipos_processo` VALUES(100000919, 'Administração Prisional: Gestão de Segurança Externa');
INSERT INTO `tb_tipos_processo` VALUES(100000920, 'Administração Prisional: Segurança Interna - Segurança Geral');
INSERT INTO `tb_tipos_processo` VALUES(100000921, 'Segurança Interna: Segurança Preventiva');
INSERT INTO `tb_tipos_processo` VALUES(100000922, 'Administração Prisional: Controle de Distribuição. Uso de Equipamentos de Segurança');
INSERT INTO `tb_tipos_processo` VALUES(100000923, 'Administração Prisional: Distribuição de Agentes de Segurança');
INSERT INTO `tb_tipos_processo` VALUES(100000924, 'Admissão do Preso');
INSERT INTO `tb_tipos_processo` VALUES(100000925, 'Administração Prisional: Revista em Servidores,Vis');
INSERT INTO `tb_tipos_processo` VALUES(100000926, 'Visita ao Preso');
INSERT INTO `tb_tipos_processo` VALUES(100000927, 'Administração Prisional: Vistoria em Veículos de P');
INSERT INTO `tb_tipos_processo` VALUES(100000928, 'Controle de Presos Foragidos da Unidade Prisional');
INSERT INTO `tb_tipos_processo` VALUES(100000929, 'Gestão de Vagas: Matrícula e Transferência de Presos');
INSERT INTO `tb_tipos_processo` VALUES(100000930, 'Gestão de Vagas: Ocupação das Unidades Prisionais');
INSERT INTO `tb_tipos_processo` VALUES(100000931, 'Administração Prisional: Prontuário Geral Padroniz');
INSERT INTO `tb_tipos_processo` VALUES(100000932, 'Ensino e Profissionalização: Frequência do Preso');
INSERT INTO `tb_tipos_processo` VALUES(100000933, 'Instituição de Ensino na Unidade Prisional: Avalia');
INSERT INTO `tb_tipos_processo` VALUES(100000934, 'Obras Realizadas pelo Órgão: Documentos Técnicos');
INSERT INTO `tb_tipos_processo` VALUES(100000935, 'Obras Realizadas pelo Órgão: Documentos Financeiro');
INSERT INTO `tb_tipos_processo` VALUES(100000936, 'Obras Realizadas pelo Órgão: Celebração de Convêni');
INSERT INTO `tb_tipos_processo` VALUES(100000937, 'Repasse de Verba e Doação para Execução de Obras: ');
INSERT INTO `tb_tipos_processo` VALUES(100000938, 'Repasse de Verba e Doação para Execução de Obras: ');
INSERT INTO `tb_tipos_processo` VALUES(100000939, 'Repasse de Verbas Federais para Execução de Obras:');
INSERT INTO `tb_tipos_processo` VALUES(100000940, 'Repasse de Verbas Federais para Execução de Obras:');
INSERT INTO `tb_tipos_processo` VALUES(100000941, 'Estudos, Programas e Projetos de Obras Públicas: P');
INSERT INTO `tb_tipos_processo` VALUES(100000942, 'Estudos, Programas e Projetos de Obras Públicas: P');
INSERT INTO `tb_tipos_processo` VALUES(100000943, 'Estudos e Programas de Obras Públicas');
INSERT INTO `tb_tipos_processo` VALUES(100000944, 'Construção e Conservação de Obras Públicas: Levant');
INSERT INTO `tb_tipos_processo` VALUES(100000945, 'RH: Carteira de Identidade Funcional e outras Identificações');
INSERT INTO `tb_tipos_processo` VALUES(100000946, 'RH: Certidão Nada Consta - sem pendências');
INSERT INTO `tb_tipos_processo` VALUES(100000947, 'RH: Certidão Nada Consta - com pendências');
INSERT INTO `tb_tipos_processo` VALUES(100000948, 'RH: Posse e Exercício - Cargo Comissionado');
INSERT INTO `tb_tipos_processo` VALUES(100000949, 'Política Ambiental');
INSERT INTO `tb_tipos_processo` VALUES(100000950, 'RH: Posse - Prorrogação');
INSERT INTO `tb_tipos_processo` VALUES(100000951, 'IDENE - Reservatórios de 500 Litros - Município');
INSERT INTO `tb_tipos_processo` VALUES(100000952, 'IDENE - Reservatórios de 500 Litros - Associações');
INSERT INTO `tb_tipos_processo` VALUES(100000953, 'Sindicância Administrativa Investigatória');
INSERT INTO `tb_tipos_processo` VALUES(100000954, 'Sindicância Administrativa Punitiva');
INSERT INTO `tb_tipos_processo` VALUES(100000955, 'Sindicância Administrativa Patrimonial');
INSERT INTO `tb_tipos_processo` VALUES(100000956, 'Processo Administrativo Disciplinar');
INSERT INTO `tb_tipos_processo` VALUES(100000957, 'RH: Nomeação - Cargo Comissionado');
INSERT INTO `tb_tipos_processo` VALUES(100000958, 'RH: Designação - Função Gratificada');
INSERT INTO `tb_tipos_processo` VALUES(100000959, 'Gestão da Qualidade: Avaliação Interlaboratorial');
INSERT INTO `tb_tipos_processo` VALUES(100000960, 'RH: Afastamento para Exercício de Mandato Eletivo');
INSERT INTO `tb_tipos_processo` VALUES(100000961, 'Estudos, Projetos e Zoneamento Ambiental: Gestão T');
INSERT INTO `tb_tipos_processo` VALUES(100000962, 'Estudos, Projetos e Zoneamento Ambiental: ICMS Eco');
INSERT INTO `tb_tipos_processo` VALUES(100000963, 'Estudos, Projetos e Zoneamento Ambiental: Estudos ');
INSERT INTO `tb_tipos_processo` VALUES(100000964, 'FEAM - Estudos Técnicos Ambientais: Fechamento de ');
INSERT INTO `tb_tipos_processo` VALUES(100000965, 'Gestão de Gastos: Água e Esgoto');
INSERT INTO `tb_tipos_processo` VALUES(100000966, 'Gestão de Gastos: Energia Elétrica');
INSERT INTO `tb_tipos_processo` VALUES(100000967, 'RH: Transferência');
INSERT INTO `tb_tipos_processo` VALUES(100000968, 'Estágio Remunerado - Convênio com Instituição de E');
INSERT INTO `tb_tipos_processo` VALUES(100000969, 'Gestão das Atividades Contábeis: Normatização e Or');
INSERT INTO `tb_tipos_processo` VALUES(100000970, 'Controle das Atividades Contábeis: Certificação Co');
INSERT INTO `tb_tipos_processo` VALUES(100000971, 'Atendimento Judiciário Socioeducativo: Prontuário ');
INSERT INTO `tb_tipos_processo` VALUES(100000973, 'Atendimento Judiciário Socioeducativo: Plano Indiv');
INSERT INTO `tb_tipos_processo` VALUES(100000974, 'Atendimento Judiciário Socioeducativo: Evolução do');
INSERT INTO `tb_tipos_processo` VALUES(100000975, 'Atendimento Judiciário Socioeducativo: Cadastro de');
INSERT INTO `tb_tipos_processo` VALUES(100000976, 'Atendimento Judiciário Socioeducativo: Instrumento');
INSERT INTO `tb_tipos_processo` VALUES(100000977, 'Gestão de Vagas Socioeducativas: Solicitação de Va');
INSERT INTO `tb_tipos_processo` VALUES(100000978, 'Gestão de Vagas Socioeducativas: Panorama da Ocupa');
INSERT INTO `tb_tipos_processo` VALUES(100000979, 'Gestão de Vagas Socioeducativas: Movimentação de A');
INSERT INTO `tb_tipos_processo` VALUES(100000980, 'Gestão de Vagas Socioeducativas: Movimentação de A');
INSERT INTO `tb_tipos_processo` VALUES(100000981, 'Orientação Socioeducativa: Projeto Político Pedagó');
INSERT INTO `tb_tipos_processo` VALUES(100000982, 'Intercâmbio entre Policiais e Adolescentes em Cump');
INSERT INTO `tb_tipos_processo` VALUES(100000983, 'Gestão da Informação e Pesquisa: Informações sobre');
INSERT INTO `tb_tipos_processo` VALUES(100000984, 'Gestão de Parcerias para Atendimento Socioeducativ');
INSERT INTO `tb_tipos_processo` VALUES(100000985, 'SEMAD Protocolo SUPRAM - ASF');
INSERT INTO `tb_tipos_processo` VALUES(100000986, 'SEMAD Protocolo SUPRAM - JEQ');
INSERT INTO `tb_tipos_processo` VALUES(100000987, 'SEMAD Protocolo SUPRAM - LM');
INSERT INTO `tb_tipos_processo` VALUES(100000988, 'SEMAD Protocolo SUPRAM - NOR');
INSERT INTO `tb_tipos_processo` VALUES(100000989, 'SEMAD Protocolo SUPRAM - NM');
INSERT INTO `tb_tipos_processo` VALUES(100000990, 'SEMAD Protocolo SUPRAM - ZM');
INSERT INTO `tb_tipos_processo` VALUES(100000991, 'SEMAD Protocolo - SUPPRI');
INSERT INTO `tb_tipos_processo` VALUES(100000992, 'Avaliação Educacional : Avaliação da Qualidade de ');
INSERT INTO `tb_tipos_processo` VALUES(100000993, 'Políticas Educacionais: Planejamento e Gestão de P');
INSERT INTO `tb_tipos_processo` VALUES(100000994, 'Educação Básica - Temáticas Especiais de Ensino: E');
INSERT INTO `tb_tipos_processo` VALUES(100000995, 'Educação Básica - Temáticas Especiais de Ensino: R');
INSERT INTO `tb_tipos_processo` VALUES(100000996, 'Educação Básica - Temáticas Especiais de Ensino: E');
INSERT INTO `tb_tipos_processo` VALUES(100000997, 'Educação Básica - Temáticas Especiais de Ensino: E');
INSERT INTO `tb_tipos_processo` VALUES(100000998, 'Educação Especial: Atendimento Educacional Especia');
INSERT INTO `tb_tipos_processo` VALUES(100000999, 'Educação Especial: Monitoramento Social');
INSERT INTO `tb_tipos_processo` VALUES(100001000, 'Educação de Jovens e Adultos: Banca Permanente de ');
INSERT INTO `tb_tipos_processo` VALUES(100001001, 'Educação de Jovens e Adultos: Alfabetização de Jov');
INSERT INTO `tb_tipos_processo` VALUES(100001002, 'Educação de Jovens e Adultos: Ensino Semipresencia');
INSERT INTO `tb_tipos_processo` VALUES(100001003, 'Educação de Jovens e Adultos: Exames Supletivos - ');
INSERT INTO `tb_tipos_processo` VALUES(100001004, 'Educação Profissional: Programa de Educação Profis');
INSERT INTO `tb_tipos_processo` VALUES(100001005, 'Ensino Fundamental: Implantação da Política do Ens');
INSERT INTO `tb_tipos_processo` VALUES(100001006, 'Ensino Fundamental: Implantação, Capacitação e Mon');
INSERT INTO `tb_tipos_processo` VALUES(100001007, 'Ensino Médio: Conteúdo Básico Comum (CBC)');
INSERT INTO `tb_tipos_processo` VALUES(100001008, 'Ensino Médio: Programa do Ensino Médio');
INSERT INTO `tb_tipos_processo` VALUES(100001009, 'Ensino Médio: Incentivo para Conclusão do Ensino M');
INSERT INTO `tb_tipos_processo` VALUES(100001010, 'Acompanhamento da Vida Escolar: Avaliação e Result');
INSERT INTO `tb_tipos_processo` VALUES(100001011, 'Funcionamento Escolar: Planejamento de Curso');
INSERT INTO `tb_tipos_processo` VALUES(100001012, 'Funcionamento Escolar: Documentação da Escola');
INSERT INTO `tb_tipos_processo` VALUES(100001013, 'Funcionamento Escolar: Inspeção');
INSERT INTO `tb_tipos_processo` VALUES(100001014, 'Funcionamento Escolar - Normas de escrituração esc');
INSERT INTO `tb_tipos_processo` VALUES(100001015, 'Regularização da Vida Escolar: Análise da Vida Esc');
INSERT INTO `tb_tipos_processo` VALUES(100001016, 'Regularidade de Funcionamento da Escola: Funcionam');
INSERT INTO `tb_tipos_processo` VALUES(100001017, 'Regularidade de Funcionamento da Escola: Processos');
INSERT INTO `tb_tipos_processo` VALUES(100001018, 'Regularidade de Funcionamento da Escola: Registro');
INSERT INTO `tb_tipos_processo` VALUES(100001019, 'Regularidade de Funcionamento da Escola: Registro,');
INSERT INTO `tb_tipos_processo` VALUES(100001021, 'Equivalência de Estudos:  Análise de Documentos Es');
INSERT INTO `tb_tipos_processo` VALUES(100001022, 'Gestão Escolar: Processo de Eleição dos Colegiados');
INSERT INTO `tb_tipos_processo` VALUES(100001023, 'Gestão Escolar: Processo de Indicação de Diretor e');
INSERT INTO `tb_tipos_processo` VALUES(100001024, 'Gestão Escolar: Avaliação e Autoavaliação  da Gest');
INSERT INTO `tb_tipos_processo` VALUES(100001025, 'Gestão Escolar: Aprimoramento da Gestão Escolar (P');
INSERT INTO `tb_tipos_processo` VALUES(100001026, 'Processo de Autorização: Autorização de Funcioname');
INSERT INTO `tb_tipos_processo` VALUES(100001027, 'Processo de Autorização: Credenciamento da Entidad');
INSERT INTO `tb_tipos_processo` VALUES(100001028, 'Processo de Autorização: Mudança de Denominação do');
INSERT INTO `tb_tipos_processo` VALUES(100001029, 'Processo de Autorização: Mudança de Denominação do');
INSERT INTO `tb_tipos_processo` VALUES(100001030, 'Processo de Autorização: Escolas Munic. e Part.: E');
INSERT INTO `tb_tipos_processo` VALUES(100001031, 'Processo de Autorização: Mudança da Entidade Mante');
INSERT INTO `tb_tipos_processo` VALUES(100001032, 'Processo de Autorização: Mudança de Prédio');
INSERT INTO `tb_tipos_processo` VALUES(100001033, 'Processo de Autorização: Reconhecimento de Curso/N');
INSERT INTO `tb_tipos_processo` VALUES(100001034, 'Processo de Autorização: Prorrogação da Autorizaçã');
INSERT INTO `tb_tipos_processo` VALUES(100001035, 'Processo de Autorização: Recredenciamento da Entid');
INSERT INTO `tb_tipos_processo` VALUES(100001036, 'Processo de Autorização: Turma Vinculada');
INSERT INTO `tb_tipos_processo` VALUES(100001037, 'Cadastro Escolar: Divulgação do Cadastro Escolar');
INSERT INTO `tb_tipos_processo` VALUES(100001038, 'Plano de Atendimento Escolar: Implantação de Curso');
INSERT INTO `tb_tipos_processo` VALUES(100001039, 'Plano de Atendimento Escolar: Implantação de Educa');
INSERT INTO `tb_tipos_processo` VALUES(100001040, 'Plano de Atendimento Escolar: Extensão dos Anos In');
INSERT INTO `tb_tipos_processo` VALUES(100001041, 'Plano de Atendimento Escolar: Implantação de Nível');
INSERT INTO `tb_tipos_processo` VALUES(100001042, 'Plano de Atendimento Escolar: Proposta de Criação ');
INSERT INTO `tb_tipos_processo` VALUES(100001043, 'Plano de Atendimento Escolar: Implantação de Segun');
INSERT INTO `tb_tipos_processo` VALUES(100001044, 'Plano de Atendimento Escolar: Integração de Escola');
INSERT INTO `tb_tipos_processo` VALUES(100001045, 'Plano de Atendimento Escolar: Paralisação e Encerr');
INSERT INTO `tb_tipos_processo` VALUES(100001046, 'Suprimento Escolar: Manutenção e Custeio da Escola');
INSERT INTO `tb_tipos_processo` VALUES(100001047, 'Suprimento Escolar: Repasse de Dinheiro Direto par');
INSERT INTO `tb_tipos_processo` VALUES(100001048, 'Suprimento Escolar: Alimentação Escolar (Merenda)');
INSERT INTO `tb_tipos_processo` VALUES(100001049, 'Suprimento Escolar: Transporte Escolar');
INSERT INTO `tb_tipos_processo` VALUES(100001050, 'Capacitação e Inclusão Social: Cursos Profissional');
INSERT INTO `tb_tipos_processo` VALUES(100001051, 'Capacitação e Inclusão Social: Projetos Culturais ');
INSERT INTO `tb_tipos_processo` VALUES(100001052, 'Suporte aos Municípios: Promoção de Debates Juveni');
INSERT INTO `tb_tipos_processo` VALUES(100001053, 'Formulação de Políticas Públicas: Criação de Conse');
INSERT INTO `tb_tipos_processo` VALUES(100001054, 'Informações Educacionais: Cadastro de Estabelecime');
INSERT INTO `tb_tipos_processo` VALUES(100001055, 'Informações Educacionais: Coleta de Dados');
INSERT INTO `tb_tipos_processo` VALUES(100001056, 'RH: Estágio Remunerado - Divulgação e seleção');
INSERT INTO `tb_tipos_processo` VALUES(100001057, 'RH: Estágio Remunerado - Solicitação de Vaga');
INSERT INTO `tb_tipos_processo` VALUES(100001058, 'RH: Estágio Remunerado - Encerramento de Estágio');
INSERT INTO `tb_tipos_processo` VALUES(100001059, 'RH: Afastamento para Serviço Militar');
INSERT INTO `tb_tipos_processo` VALUES(100001060, 'RH: Frequência Anual');
INSERT INTO `tb_tipos_processo` VALUES(100001061, 'RH: Promoção por Escolaridade Adicional');
INSERT INTO `tb_tipos_processo` VALUES(100001062, 'Processo de Autorização: Alteração na Entidade Man');
INSERT INTO `tb_tipos_processo` VALUES(100001063, 'Processo de Autorização: Reinício de Atividades (E');
INSERT INTO `tb_tipos_processo` VALUES(100001064, 'Processo de Autorização: Encerramento (Escolas Par');
INSERT INTO `tb_tipos_processo` VALUES(100001066, 'RH: Conversão de Férias Prêmio em Espécie pós Exon');
INSERT INTO `tb_tipos_processo` VALUES(100001067, 'RH: Solicitação de crachá - Cidade Administrativa');
INSERT INTO `tb_tipos_processo` VALUES(100001068, 'RH: Contrato Administrativo - Aditivo');
INSERT INTO `tb_tipos_processo` VALUES(100001069, 'RH: Contrato Administrativo Perito - Pré Qualifica');
INSERT INTO `tb_tipos_processo` VALUES(100001070, 'RH: Concurso Público - Informação de candidato');
INSERT INTO `tb_tipos_processo` VALUES(100001071, 'RH: Contagem em dobro de Férias Prêmio para aposen');
INSERT INTO `tb_tipos_processo` VALUES(100001072, 'Inteligência do Sistema Socioeducativo: Produção d');
INSERT INTO `tb_tipos_processo` VALUES(100001073, 'Inteligência do Sistema Socioeducativo: Contados c');
INSERT INTO `tb_tipos_processo` VALUES(100001074, 'RH: Avaliação de Desempenho dos Gestores Públicos');
INSERT INTO `tb_tipos_processo` VALUES(100001075, 'RH: Nomeação - Tornar sem efeito');
INSERT INTO `tb_tipos_processo` VALUES(100001076, 'SESP - Internação Por Prazo Indeterminado');
INSERT INTO `tb_tipos_processo` VALUES(100001077, 'SESP - Internação Sanção');
INSERT INTO `tb_tipos_processo` VALUES(100001078, 'SESP - Semiliberdade');
INSERT INTO `tb_tipos_processo` VALUES(100001079, 'RH: Avaliação de Desempenho');
INSERT INTO `tb_tipos_processo` VALUES(100001080, 'RH: Exercício - Prorrogação');
INSERT INTO `tb_tipos_processo` VALUES(100001081, 'RH: Alteração de Data Férias Prêmio');
INSERT INTO `tb_tipos_processo` VALUES(100001082, 'RH: Opção Remuneratória');
INSERT INTO `tb_tipos_processo` VALUES(100001083, 'SISEMA - Plano Individual de Fiscalização - PIF');
INSERT INTO `tb_tipos_processo` VALUES(100001084, 'SISEMA - Gratificação pelo Desenvolvimento de Atividade de Fiscalização - GDAF');
INSERT INTO `tb_tipos_processo` VALUES(100001085, 'Inscrição do Débito em Dívida Ativa Não Tributária');
INSERT INTO `tb_tipos_processo` VALUES(100001086, 'Inscrição do Débito em Dívida Ativa Não Tributária');
INSERT INTO `tb_tipos_processo` VALUES(100001087, 'Inscrição do Débito em Dívida Ativa Não Tributária');
INSERT INTO `tb_tipos_processo` VALUES(100001088, 'RH: Missão Governamental');
INSERT INTO `tb_tipos_processo` VALUES(100001089, 'Modernização Institucional: Monitoramento do Desem');
INSERT INTO `tb_tipos_processo` VALUES(100001090, 'Qualificação como OSCIP');
INSERT INTO `tb_tipos_processo` VALUES(100001092, 'Cooperação para o Programa de Parcerias Público-Pr');
INSERT INTO `tb_tipos_processo` VALUES(100001093, 'Estruturas Institucionais do Programa Parcerias Pú');
INSERT INTO `tb_tipos_processo` VALUES(100001094, 'Estruturas Institucionais do Programa Parcerias Pú');
INSERT INTO `tb_tipos_processo` VALUES(100001095, 'Estruturas Institucionais do Programa Parcerias Pú');
INSERT INTO `tb_tipos_processo` VALUES(100001096, 'Diretrizes e Práticas para Estruturação de Projeto');
INSERT INTO `tb_tipos_processo` VALUES(100001097, 'Implementação de Novos Modelos para Execução de Po');
INSERT INTO `tb_tipos_processo` VALUES(100001098, 'Organização Administrativa: Informações Institucionais');
INSERT INTO `tb_tipos_processo` VALUES(100001099, 'Organização Administrativa: Normas e Manuais');
INSERT INTO `tb_tipos_processo` VALUES(100001100, 'Recursos para Avaliação de Desempenho');
INSERT INTO `tb_tipos_processo` VALUES(100001101, 'Desenvolvimento Regional e Urbano: Processo Admini');
INSERT INTO `tb_tipos_processo` VALUES(100001102, 'Patrimônio Imobiliário: Locação (Inclusive de Imóv');
INSERT INTO `tb_tipos_processo` VALUES(100001103, 'Avaliação para Concessão de Benefícios e Prevenção à Saúde (Prontuário Médico)');
INSERT INTO `tb_tipos_processo` VALUES(100001104, 'Assistência à Saúde: Concessão de Insalubridade e Periculosidade');
INSERT INTO `tb_tipos_processo` VALUES(100001105, 'Inspeções Periódicas de Saúde');
INSERT INTO `tb_tipos_processo` VALUES(100001106, 'Assistência Médica Suplementar');
INSERT INTO `tb_tipos_processo` VALUES(100001107, 'Produção Editorial: Coordenação da Edição de Publi');
INSERT INTO `tb_tipos_processo` VALUES(100001108, 'Política Estadual de Transparência Pública e Contr');
INSERT INTO `tb_tipos_processo` VALUES(100001109, 'Política Estadual de Transparência Pública e Contr');
INSERT INTO `tb_tipos_processo` VALUES(100001110, 'Incremento da Transparência Pública. Informação In');
INSERT INTO `tb_tipos_processo` VALUES(100001111, 'Incremento da Transparência Pública.Informação Ins');
INSERT INTO `tb_tipos_processo` VALUES(100001112, 'Parcerias para a Transparência Pública e Controle ');
INSERT INTO `tb_tipos_processo` VALUES(100001113, 'Parcerias para a Transparência Pública e Controle ');
INSERT INTO `tb_tipos_processo` VALUES(100001114, 'Produção e Desenvolvimento de Tecnologias de Trans');
INSERT INTO `tb_tipos_processo` VALUES(100001115, 'Produção e Desenvolvimento de Tecnologias de Trans');
INSERT INTO `tb_tipos_processo` VALUES(100001116, 'Política Estadual de Prevenção e Combate à Corrupç');
INSERT INTO `tb_tipos_processo` VALUES(100001117, 'Política Estadual de Prevenção e Combate à Corrupç');
INSERT INTO `tb_tipos_processo` VALUES(100001118, 'Parcerias para a Prevenção e Combate à Corrupção: ');
INSERT INTO `tb_tipos_processo` VALUES(100001119, 'Parcerias para a Prevenção e Combate à Corrupção: ');
INSERT INTO `tb_tipos_processo` VALUES(100001120, 'Disseminação de Conteúdos Relacionados à Prevenção');
INSERT INTO `tb_tipos_processo` VALUES(100001121, 'Dados e Informações Referentes à Prevenção e ao Combate à Corrupção');
INSERT INTO `tb_tipos_processo` VALUES(100001122, 'Produção e Desenvolvimento de Tecnologia de Preven');
INSERT INTO `tb_tipos_processo` VALUES(100001123, 'Produção e Desenvolvimento de Tecnologia de Preven');
INSERT INTO `tb_tipos_processo` VALUES(100001124, 'Gestão Institucional: Organização e Funcionamento do órgão/entidade');
INSERT INTO `tb_tipos_processo` VALUES(100001125, 'Organização e Funcionamento do órgão/entidade: Reg');
INSERT INTO `tb_tipos_processo` VALUES(100001126, 'Organização e Funcionamento do órgão/entidade: Audiências. Despachos. Reuniões');
INSERT INTO `tb_tipos_processo` VALUES(100001127, 'Gestão Institucional: Comissões Técnicas. Conselhos. Grupos de Trabalho. Juntas. Comitês. Câmaras');
INSERT INTO `tb_tipos_processo` VALUES(100001128, 'Divulgação Institucional do Governo: Campanhas Pub');
INSERT INTO `tb_tipos_processo` VALUES(100001129, 'Publicação de Matérias no Diário Oficial do Estado de Minas Gerais');
INSERT INTO `tb_tipos_processo` VALUES(100001130, 'Divulgação Institucional do Governo: Material Inst');
INSERT INTO `tb_tipos_processo` VALUES(100001131, 'Divulgação Institucional do Governo: Material Inst');
INSERT INTO `tb_tipos_processo` VALUES(100001132, 'Assessoria de Imprensa');
INSERT INTO `tb_tipos_processo` VALUES(100001133, 'Relacionamento com a Imprensa: Produção e Veiculaç');
INSERT INTO `tb_tipos_processo` VALUES(100001134, 'Acompanhamento da Versão On-line de Jornais Impres');
INSERT INTO `tb_tipos_processo` VALUES(100001135, 'Comunicação Social: Divulgação Interna');
INSERT INTO `tb_tipos_processo` VALUES(100001136, 'Produção Editorial: Coordenação da Edição de Publi');
INSERT INTO `tb_tipos_processo` VALUES(100001137, 'Produção Editorial: Distribuição, Promoção e Divul');
INSERT INTO `tb_tipos_processo` VALUES(100001138, 'Credenciamento para Celebração de Convênios no CAG');
INSERT INTO `tb_tipos_processo` VALUES(100001139, 'Credenciamento para Celebração de Convênios no CAG');
INSERT INTO `tb_tipos_processo` VALUES(100001140, 'Credenciamento para Celebração de Convênios no CAG');
INSERT INTO `tb_tipos_processo` VALUES(100001141, 'Credenciamento para Celebração de Convênios no CAG');
INSERT INTO `tb_tipos_processo` VALUES(100001142, 'Credenciamento para Celebração de Convênios no CAG');
INSERT INTO `tb_tipos_processo` VALUES(100001143, 'Credenciamento para Celebração de Convênios no CAG');
INSERT INTO `tb_tipos_processo` VALUES(100001144, 'Credenciamento para Celebração de Convênios no CAG');
INSERT INTO `tb_tipos_processo` VALUES(100001145, 'Gestão Institucional: Relatórios de Atividades');
INSERT INTO `tb_tipos_processo` VALUES(100001146, 'Gestão do Atendimento ao Cidadão: Avaliação e Moni');
INSERT INTO `tb_tipos_processo` VALUES(100001147, 'Gestão do Atendimento ao Cidadão: Estruturação e P');
INSERT INTO `tb_tipos_processo` VALUES(100001148, 'Gestão do Atendimento ao Cidadão: Atendimento e Orientação');
INSERT INTO `tb_tipos_processo` VALUES(100001149, 'Atendimento ao Cidadão. Gestão da Informação e Recursos de Tecnologia da Informação e Comunicação');
INSERT INTO `tb_tipos_processo` VALUES(100001150, 'Outras Atividades/Transações Referentes a Organização e Funcionamento: Informações sobre o Órgão');
INSERT INTO `tb_tipos_processo` VALUES(100001151, 'DEER - Recursos de multas de transporte coletivo i');
INSERT INTO `tb_tipos_processo` VALUES(100001152, 'Recursos de Multas de Trânsito');
INSERT INTO `tb_tipos_processo` VALUES(100001153, 'Assessoramento Jurídico: Parecer');
INSERT INTO `tb_tipos_processo` VALUES(100001154, 'Assessoramento Jurídico: Parecer sobre Processo Ad');
INSERT INTO `tb_tipos_processo` VALUES(100001155, 'Assessoramento Jurídico: Parecer sobre Processo do');
INSERT INTO `tb_tipos_processo` VALUES(100001156, 'Assessoramento Jurídico: Parecer Normativo');
INSERT INTO `tb_tipos_processo` VALUES(100001157, 'Assessoramento Jurídico: Nota Jurídica');
INSERT INTO `tb_tipos_processo` VALUES(100001158, 'Corregedoria: Análise Preliminar');
INSERT INTO `tb_tipos_processo` VALUES(100001159, 'RH: Contrato Administrativo Perito - Pré Qualifica');
INSERT INTO `tb_tipos_processo` VALUES(100001160, 'RH: Contrato Administrativo - Rescisão');
INSERT INTO `tb_tipos_processo` VALUES(100001161, 'RH: Posse e Exercício - Função Gratificada');
INSERT INTO `tb_tipos_processo` VALUES(100001162, 'RH: Desligamento por Morte');
INSERT INTO `tb_tipos_processo` VALUES(100001163, 'RH: Saúde do Servidor: Perícia Ex-Officio');
INSERT INTO `tb_tipos_processo` VALUES(100001164, 'RH: Posse - Desistência');
INSERT INTO `tb_tipos_processo` VALUES(100001165, 'RH: Acúmulo de Cargos');
INSERT INTO `tb_tipos_processo` VALUES(100001166, 'RH: Exoneração ou Dispensa a Pedido');
INSERT INTO `tb_tipos_processo` VALUES(100001167, 'RH: Capacidade Laborativa');
INSERT INTO `tb_tipos_processo` VALUES(100001168, 'RH: Adicional de Periculosidade');
INSERT INTO `tb_tipos_processo` VALUES(100001169, 'RH: Licença por Motivo de Doença em Pessoa da Famí');
INSERT INTO `tb_tipos_processo` VALUES(100001170, 'RH: Exoneração ou Dispensa Ex Officio');
INSERT INTO `tb_tipos_processo` VALUES(100001171, 'Lei Estadual de Incentivo à Cultura: Projetos apro');
INSERT INTO `tb_tipos_processo` VALUES(100001172, 'Lei Estadual de Incentivo à Cultura: Projetos apro');
INSERT INTO `tb_tipos_processo` VALUES(100001173, 'Processo Recebido Externamente (a classificar)');
INSERT INTO `tb_tipos_processo` VALUES(100001174, 'Cooperação Administrativa e Técnica');
INSERT INTO `tb_tipos_processo` VALUES(100001175, 'RH: Ordem de Pagamento Especial (OPE): vencimentos deixados');
INSERT INTO `tb_tipos_processo` VALUES(100001176, 'Processo de Autorização: Renovação do Reconhecimen');
INSERT INTO `tb_tipos_processo` VALUES(100001177, 'Processo de Autorização: Autorização de Funcioname');
INSERT INTO `tb_tipos_processo` VALUES(100001178, 'Processo de Autorização: Prorrogação do Reconhecim');
INSERT INTO `tb_tipos_processo` VALUES(100001179, 'Processo de Autorização: Prorrogação da Renovação ');
INSERT INTO `tb_tipos_processo` VALUES(100001180, 'Processo de Autorização: Prorrogação do Credenciam');
INSERT INTO `tb_tipos_processo` VALUES(100001181, 'Processo de Autorização: Prorrogação do Recredenci');
INSERT INTO `tb_tipos_processo` VALUES(100001182, 'Processo de Autorização: Especialização técnica de');
INSERT INTO `tb_tipos_processo` VALUES(100001183, 'Processo de Autorização: Funcionamento de Pólo de ');
INSERT INTO `tb_tipos_processo` VALUES(100001184, 'Processo de Autorização: Segundo Endereço da Escol');
INSERT INTO `tb_tipos_processo` VALUES(100001185, 'Processo de Autorização: Ampliação da Rede Física ');
INSERT INTO `tb_tipos_processo` VALUES(100001186, 'IDENE - Doação de bens de emenda parlamentar - Ass');
INSERT INTO `tb_tipos_processo` VALUES(100001187, 'IDENE - Doação de bens de emenda parlamentar - Mun');
INSERT INTO `tb_tipos_processo` VALUES(100001188, 'FEAM - Gestão Técnica de Projetos Ambientais: Decl');
INSERT INTO `tb_tipos_processo` VALUES(100001189, 'Recebimento e Análise de Denúncia de Correição: Análise de Denúncia');
INSERT INTO `tb_tipos_processo` VALUES(100001190, 'Recebimento e Análise de Denúncia de Correição: Ex');
INSERT INTO `tb_tipos_processo` VALUES(100001191, 'Recebimento e Análise de Denúncia de Correição: Ex');
INSERT INTO `tb_tipos_processo` VALUES(100001192, 'Recebimento e Análise de Denúncia de Correição: Ex');
INSERT INTO `tb_tipos_processo` VALUES(100001193, 'Recebimento e Análise de Denúncia de Correição: Expediente de Conduta Funcional Irregular');
INSERT INTO `tb_tipos_processo` VALUES(100001194, 'Recebimento e Análise de Denúncia de Correição: Expediente de Servidor - Abandono de Cargo');
INSERT INTO `tb_tipos_processo` VALUES(100001195, 'Recebimento e Análise de Denúncia de Correição: In');
INSERT INTO `tb_tipos_processo` VALUES(100001196, 'Recebimento e Análise de Denúncia de Correição: Po');
INSERT INTO `tb_tipos_processo` VALUES(100001197, 'Recebimento e Análise de Denúncia de Correição: Do');
INSERT INTO `tb_tipos_processo` VALUES(100001198, 'Recebimento e Análise de Denúncia de Correição: Ex');
INSERT INTO `tb_tipos_processo` VALUES(100001199, 'Recebimento e Análise de Denúncia de Correição: Ex');
INSERT INTO `tb_tipos_processo` VALUES(100001200, 'Recebimento e Análise de Denúncia de Correição: Ex');
INSERT INTO `tb_tipos_processo` VALUES(100001201, 'Recebimento e Análise de Denúncia de Correição: Ex');
INSERT INTO `tb_tipos_processo` VALUES(100001202, 'Correição: Procedimentos Administrativos Disciplinares');
INSERT INTO `tb_tipos_processo` VALUES(100001203, 'Higiene e Segurança do Trabalho');
INSERT INTO `tb_tipos_processo` VALUES(100001204, 'Prevenção de Acidentes de Trabalho. Comissão Inter');
INSERT INTO `tb_tipos_processo` VALUES(100001205, 'Programas Preventivos de Saúde Laboral');
INSERT INTO `tb_tipos_processo` VALUES(100001206, 'Auditoria Assistencial');
INSERT INTO `tb_tipos_processo` VALUES(100001207, 'Cadastro de Comunicação de Acidente de Trabalho - CAT');
INSERT INTO `tb_tipos_processo` VALUES(100001208, 'Gestão de Desempenho dos Servidores: Normatização da Avaliação de Desempenho');
INSERT INTO `tb_tipos_processo` VALUES(100001209, 'Gestão de Desempenho dos Servidores: Avaliação de Desempenho do Servidor');
INSERT INTO `tb_tipos_processo` VALUES(100001210, 'Gestão de Desempenho dos Servidores: Avaliação de Desempenho Individual');
INSERT INTO `tb_tipos_processo` VALUES(100001211, 'Gestão de Desempenho dos Servidores: Avaliação de Desempenho do Gestor Público');
INSERT INTO `tb_tipos_processo` VALUES(100001212, 'Outras Atividades/Transações Referentes à Gestão de Pessoas: Horário de Expediente');
INSERT INTO `tb_tipos_processo` VALUES(100001213, 'Outras Atividades/Transações Referentes à Gestão de Pessoas: Controle de Frequência');
INSERT INTO `tb_tipos_processo` VALUES(100001214, 'Missões Fora da Sede. Viagens a Serviço: Sem Ônus para a Instituição');
INSERT INTO `tb_tipos_processo` VALUES(100001215, 'Missões Fora da Sede. Viagens a Serviço: Com Ônus ');
INSERT INTO `tb_tipos_processo` VALUES(100001216, 'Incentivos Funcionais: Prêmios (Concessões de Meda');
INSERT INTO `tb_tipos_processo` VALUES(100001217, 'Outras Atividades/Transações Referentes à Gestão de Pessoas: Delegações de Competência. Procuração');
INSERT INTO `tb_tipos_processo` VALUES(100001218, 'Ações Judiciais Movidas por Servidores Públicos (Estatutário e Celetista): Informação Técnica');
INSERT INTO `tb_tipos_processo` VALUES(100001219, 'Ações Judiciais Movidas por Servidores Públicos (Estatutário e Celetista):Cumprimento de Decisão jud');
INSERT INTO `tb_tipos_processo` VALUES(100001220, 'Movimentos Reivindicatórios: Greves e Paralisações');
INSERT INTO `tb_tipos_processo` VALUES(100001221, 'Produção de Tecnologias de Auditoria: Desenvolvime');
INSERT INTO `tb_tipos_processo` VALUES(100001222, 'Produção de Tecnologias de Auditoria: Divulgação');
INSERT INTO `tb_tipos_processo` VALUES(100001223, 'Acompanhamento da Aplicação de Normas e Procedimentos de Auditoria Operacional');
INSERT INTO `tb_tipos_processo` VALUES(100001224, 'Gerenciamento das Atividades de Auditoria');
INSERT INTO `tb_tipos_processo` VALUES(100001225, 'Auditoria Interna Especial por Determinação Superi');
INSERT INTO `tb_tipos_processo` VALUES(100001226, 'Auditorias Especiais: Acolhimento e Apuração da De');
INSERT INTO `tb_tipos_processo` VALUES(100001227, 'Prestação Anual de Contas do Governador');
INSERT INTO `tb_tipos_processo` VALUES(100001228, 'Abertura de Vista nas Contas do Governador promovi');
INSERT INTO `tb_tipos_processo` VALUES(100001229, 'Auditoria de Contas: Auditoria do Relatório de Ges');
INSERT INTO `tb_tipos_processo` VALUES(100001230, 'Auditoria de Contas: Auditoria Contábil');
INSERT INTO `tb_tipos_processo` VALUES(100001231, 'Auditoria de Contas: Tomada de Contas Especial');
INSERT INTO `tb_tipos_processo` VALUES(100001232, 'Auditoria de Contratos de Gestão: Auditoria de Aco');
INSERT INTO `tb_tipos_processo` VALUES(100001233, 'Avaliação de Gestão e Resultados de Termos de Parc');
INSERT INTO `tb_tipos_processo` VALUES(100001234, 'Avaliação de Contratos de Parcerias Público Privad');
INSERT INTO `tb_tipos_processo` VALUES(100001235, 'Auditoria de Gestão: Monitoramento das Implementaç');
INSERT INTO `tb_tipos_processo` VALUES(100001236, 'Auditoria de Gestão: Auditoria de Avaliação de Imp');
INSERT INTO `tb_tipos_processo` VALUES(100001237, 'Auditoria de Gestão: Auditoria em Programas Govern');
INSERT INTO `tb_tipos_processo` VALUES(100001238, 'Auditoria de Gestão: Auditoria em Demandas Pontuais');
INSERT INTO `tb_tipos_processo` VALUES(100001239, 'Planejamento e Orçamento: Políticas Orçamentárias');
INSERT INTO `tb_tipos_processo` VALUES(100001240, 'Planejamento e Orçamento: Orçamento Fiscal');
INSERT INTO `tb_tipos_processo` VALUES(100001241, 'Planejamento e Orçamento: Orçamento de Investiment');
INSERT INTO `tb_tipos_processo` VALUES(100001242, 'Planejamento e Orçamento: Coordenação Geral: Ações');
INSERT INTO `tb_tipos_processo` VALUES(100001243, 'Gestão de Projetos Estruturadores: Planejamento e ');
INSERT INTO `tb_tipos_processo` VALUES(100001244, 'Planejamento e Orçamento: Previsão de Receita');
INSERT INTO `tb_tipos_processo` VALUES(100001245, 'Planejamento e Orçamento: Programação Orçamentária');
INSERT INTO `tb_tipos_processo` VALUES(100001246, 'Plano Plurianual de Ação Governamental - PPAG');
INSERT INTO `tb_tipos_processo` VALUES(100001247, 'Avaliação e Monitoramento do Plano Plurianual de A');
INSERT INTO `tb_tipos_processo` VALUES(100001248, 'Lei de Diretrizes Orçamentárias - LDO');
INSERT INTO `tb_tipos_processo` VALUES(100001249, 'Recomendações para Elaboração da Lei de Diretrizes');
INSERT INTO `tb_tipos_processo` VALUES(100001250, 'Lei do Orçamento Anual - LOA');
INSERT INTO `tb_tipos_processo` VALUES(100001251, 'Mensagem Anual do Governador à ALMG');
INSERT INTO `tb_tipos_processo` VALUES(100001252, 'Execuções Física e Orçamentária: Processo de Alter');
INSERT INTO `tb_tipos_processo` VALUES(100001253, 'Execução de Orçamento de Encargos Gerais do Estado');
INSERT INTO `tb_tipos_processo` VALUES(100001254, 'Execução de Orçamento de Encargos Gerais do Estado');
INSERT INTO `tb_tipos_processo` VALUES(100001255, 'Execuções Física e Orçamentária: Descentralização ');
INSERT INTO `tb_tipos_processo` VALUES(100001256, 'Execuções Física e Orçamentária: Plano Operativo. ');
INSERT INTO `tb_tipos_processo` VALUES(100001257, 'Acompanhamento de Convênios de Entrada de Recursos');
INSERT INTO `tb_tipos_processo` VALUES(100001258, 'Gestão Financeira: Elaboração de Fluxo de Caixa');
INSERT INTO `tb_tipos_processo` VALUES(100001259, 'Controle de Encargos Gerais do Estado e das Unidad');
INSERT INTO `tb_tipos_processo` VALUES(100001260, 'Prestação de Contas Mensal de Encargos Gerais do E');
INSERT INTO `tb_tipos_processo` VALUES(100001261, 'Prestação de Contas Anual de Encargos Gerais do Es');
INSERT INTO `tb_tipos_processo` VALUES(100001262, 'Prestação de Contas Mensal às Prefeituras Municipa');
INSERT INTO `tb_tipos_processo` VALUES(100001263, 'Encerramento do Exercício da Unidade Orçamentária');
INSERT INTO `tb_tipos_processo` VALUES(100001264, 'Controle de Encargos Gerais do Estado e das Unidad');
INSERT INTO `tb_tipos_processo` VALUES(100001265, 'Controle de Encargos Gerais do Estado e das Unidad');
INSERT INTO `tb_tipos_processo` VALUES(100001266, 'Certificação Mensal de Saldos e Encargos Gerais do');
INSERT INTO `tb_tipos_processo` VALUES(100001267, 'Credenciamento de Bancos para Arrecadação de Tribu');
INSERT INTO `tb_tipos_processo` VALUES(100001268, 'Controle Financeiro da Arrecadação da Receita do E');
INSERT INTO `tb_tipos_processo` VALUES(100001269, 'Registro Contábil Diário das Transferências Federa');
INSERT INTO `tb_tipos_processo` VALUES(100001270, 'Caixa do Tesouro do Estado: Posição Diária do Caix');
INSERT INTO `tb_tipos_processo` VALUES(100001271, 'Caixa do Tesouro do Estado: Fechamento Diário do C');
INSERT INTO `tb_tipos_processo` VALUES(100001272, 'Acompanhamento Diário do Mercado Financeiro');
INSERT INTO `tb_tipos_processo` VALUES(100001273, 'Registro Contábil dos Rendimentos das Aplicações F');
INSERT INTO `tb_tipos_processo` VALUES(100001274, 'Receita: Classificação da Receita Arrecadada');
INSERT INTO `tb_tipos_processo` VALUES(100001275, 'Receita: Captação de Recursos do Orçamento Geral d');
INSERT INTO `tb_tipos_processo` VALUES(100001276, 'Despesas: Reclassificação: Receitas a Restituir a ');
INSERT INTO `tb_tipos_processo` VALUES(100001277, 'Despesas: Controle e Acompanhamento de Precatórios');
INSERT INTO `tb_tipos_processo` VALUES(100001278, 'Controles Orçamentário e Financeiro da Liberação d');
INSERT INTO `tb_tipos_processo` VALUES(100001279, 'Pagamento de Taxas de Administração e Tarifas Banc');
INSERT INTO `tb_tipos_processo` VALUES(100001280, 'Acompanhamento de Registro no Cadastro Informativo');
INSERT INTO `tb_tipos_processo` VALUES(100001281, 'Acompanhamento de Registro no Cadastro Único de Co');
INSERT INTO `tb_tipos_processo` VALUES(100001282, 'Contratação de Empréstimos, Financiamentos e outra');
INSERT INTO `tb_tipos_processo` VALUES(100001283, 'Contratação de Empréstimos, Financiamentos e outra');
INSERT INTO `tb_tipos_processo` VALUES(100001284, 'Contratação de Empréstimo e Financiamento - Dívida');
INSERT INTO `tb_tipos_processo` VALUES(100001285, 'Contratação de Empréstimo e Financiamento - Dívida');
INSERT INTO `tb_tipos_processo` VALUES(100001286, 'Administração da Dívida Pública Fundada do Estado:');
INSERT INTO `tb_tipos_processo` VALUES(100001287, 'Administração da Dívida Pública Fundada do Estado:');
INSERT INTO `tb_tipos_processo` VALUES(100001288, 'Recebimento e Liberação de Recursos de Empréstimos');
INSERT INTO `tb_tipos_processo` VALUES(100001289, 'Recursos de Empréstimos Externos: Registros de Lib');
INSERT INTO `tb_tipos_processo` VALUES(100001290, 'Pagamento da Dívida: Apuração do Valor Dedutível d');
INSERT INTO `tb_tipos_processo` VALUES(100001291, 'Administração da Dívida Pública Fundada do Estado:');
INSERT INTO `tb_tipos_processo` VALUES(100001292, 'Controle e Acompanhamento Mensal da Dívida Pública');
INSERT INTO `tb_tipos_processo` VALUES(100001293, 'Controle Orçamentário Mensal da Dívida Pública Fun');
INSERT INTO `tb_tipos_processo` VALUES(100001294, 'Controle e Acompanhamento da Dívida: Controle do F');
INSERT INTO `tb_tipos_processo` VALUES(100001295, 'Participação Acionária do Estado - Governança Corp');
INSERT INTO `tb_tipos_processo` VALUES(100001296, 'Participação Acionária do Estado - Governança Corp');
INSERT INTO `tb_tipos_processo` VALUES(100001297, 'Participação Acionária do Estado - Governança Corp');
INSERT INTO `tb_tipos_processo` VALUES(100001298, 'Participação Acionária do Estado - Governança Corp');
INSERT INTO `tb_tipos_processo` VALUES(100001299, 'Participação Acionária do Estado - Governança Corp');
INSERT INTO `tb_tipos_processo` VALUES(100001300, 'Ajuste Fiscal: Regime de Recuperação Fiscal');
INSERT INTO `tb_tipos_processo` VALUES(100001301, 'Certificação Mensal de Saldos do Fundo Financeiro ');
INSERT INTO `tb_tipos_processo` VALUES(100001302, 'Registro Contábil das Contribuições ao Fundo Financeiro de Previdência da ALMG');
INSERT INTO `tb_tipos_processo` VALUES(100001303, 'Gestão das Atividades Contábeis: Normatização e Or');
INSERT INTO `tb_tipos_processo` VALUES(100001304, 'Controle das Atividades Contábeis');
INSERT INTO `tb_tipos_processo` VALUES(100001305, 'Gestão das Atividades Contábeis: Demonstrativos e ');
INSERT INTO `tb_tipos_processo` VALUES(100001306, 'Gestão das Atividades Contábeis: Responsabilidade ');
INSERT INTO `tb_tipos_processo` VALUES(100001307, 'Gestão das Atividades Contábeis: Coleta de Dados C');
INSERT INTO `tb_tipos_processo` VALUES(100001308, 'Balanço Geral do Estado');
INSERT INTO `tb_tipos_processo` VALUES(100001309, 'Atendimento e Fornecimento de documentação da Extinta Caixa Econômica do Estado (MINASCAIXA)');
INSERT INTO `tb_tipos_processo` VALUES(100001310, 'Comunicação: Serviço Postal');
INSERT INTO `tb_tipos_processo` VALUES(100001311, 'Comunicação: Sedex Nacional e Internacional');
INSERT INTO `tb_tipos_processo` VALUES(100001312, 'Comunicação: Serca/Malote');
INSERT INTO `tb_tipos_processo` VALUES(100001313, 'Comunicação: Serviço de Transporte de Cargas');
INSERT INTO `tb_tipos_processo` VALUES(100001314, 'Comunicação: Outros Serviços Postais');
INSERT INTO `tb_tipos_processo` VALUES(100001315, 'Eventos: Concursos: Documentos Técnicos');
INSERT INTO `tb_tipos_processo` VALUES(100001316, 'Eventos: Concursos: Documentos Financeiros');
INSERT INTO `tb_tipos_processo` VALUES(100001317, 'Visitas e Visitantes aos Órgãos: Visitas Técnicas');
INSERT INTO `tb_tipos_processo` VALUES(100001318, 'Visitas e Visitantes aos Órgãos: Visitas Monitorad');
INSERT INTO `tb_tipos_processo` VALUES(100001319, 'Visitas e Visitantes aos Órgãos: Visitas Monitorad');
INSERT INTO `tb_tipos_processo` VALUES(100001320, 'Outras Atividades/Transações de Gestão Institucional: Cartas de Apresentação e Recomendação');
INSERT INTO `tb_tipos_processo` VALUES(100001321, 'Outras Atividades/Transações de Gestão Institucional: Comunicados e Informes');
INSERT INTO `tb_tipos_processo` VALUES(100001322, 'Outras Atividades/Transações de Gestão Institucional: Convites Diversos');
INSERT INTO `tb_tipos_processo` VALUES(100001323, 'Outras Atividades/Transações de Gestão Institucion');
INSERT INTO `tb_tipos_processo` VALUES(100001324, 'Outras Atividades/Transações de Gestão Institucion');
INSERT INTO `tb_tipos_processo` VALUES(100001325, 'Outras Atividades/Transações de Gestão Institucional: Pedidos. Oferecimentos e Informações Diversas');
INSERT INTO `tb_tipos_processo` VALUES(100001326, 'Outras Atividades/Transações de Gestão Institucion');
INSERT INTO `tb_tipos_processo` VALUES(100001327, 'Indicação Parlamentar Saúde - Custeio');
INSERT INTO `tb_tipos_processo` VALUES(100001328, 'Indicação Parlamentar Saúde - Investimento');
INSERT INTO `tb_tipos_processo` VALUES(100001329, 'Eventos: Promovidos pelo órgão (Congressos. Conferências, Treinamentos. Workshops...)');
INSERT INTO `tb_tipos_processo` VALUES(100001330, 'RH: Exoneração Ex Officio - Decretos nº 47.606 e  nº 47.608');
INSERT INTO `tb_tipos_processo` VALUES(100001331, 'RH: Taxação - Decretos nº 47.606 e nº 47.608');
INSERT INTO `tb_tipos_processo` VALUES(100001332, 'RH: Verificação de Pendência Contábil');
INSERT INTO `tb_tipos_processo` VALUES(100001333, 'Indicação Parlamentar Saúde - Veículos');
INSERT INTO `tb_tipos_processo` VALUES(100001334, 'Patrimônio Imobiliário: Cessão, Permissão. Autoriz');
INSERT INTO `tb_tipos_processo` VALUES(100001335, 'SEAPA - Proalminas');
INSERT INTO `tb_tipos_processo` VALUES(100001336, 'Loteria _ Operações');
INSERT INTO `tb_tipos_processo` VALUES(100001338, 'RH: Contrato Administrativo - Nova Contratação');
INSERT INTO `tb_tipos_processo` VALUES(100001339, 'RH: Estágio - Encerramento');
INSERT INTO `tb_tipos_processo` VALUES(100001340, 'Ordenação Territorial: Legislação Urbanística');
INSERT INTO `tb_tipos_processo` VALUES(100001341, 'Ordenação Territorial: Planos Diretores');
INSERT INTO `tb_tipos_processo` VALUES(100001342, 'Ordenação Territorial: Gestão da Informação dos Pl');
INSERT INTO `tb_tipos_processo` VALUES(100001343, 'RH: Movimentação Interna de Servidor');
INSERT INTO `tb_tipos_processo` VALUES(100001344, 'IEF: Autorização de Pesquisa Científica');
INSERT INTO `tb_tipos_processo` VALUES(100001345, 'IEF: Cadastro de Aula de Campo');
INSERT INTO `tb_tipos_processo` VALUES(100001346, 'SEGOV - PADEM -  Programa de Apoio ao Desenvolvime');
INSERT INTO `tb_tipos_processo` VALUES(100001347, 'Gestão de Contrato: Cobrança/Notificação Extrajudi');
INSERT INTO `tb_tipos_processo` VALUES(100001348, 'Operações Financ. de Crédito: Acomp. de Reg. Cadas');
INSERT INTO `tb_tipos_processo` VALUES(100001349, 'Operações Financeiras de Crédito: Acompanhamento d');
INSERT INTO `tb_tipos_processo` VALUES(100001350, 'Parcelamento do Solo: Fiscalização');
INSERT INTO `tb_tipos_processo` VALUES(100001351, 'Anuência Prévia: Parcelamento do Solo');
INSERT INTO `tb_tipos_processo` VALUES(100001352, 'Organização Administrativa: Estudos e Propostas de Reestruturação Organizacional');
INSERT INTO `tb_tipos_processo` VALUES(100001353, 'Desenvolvimento das Microrregiões: Plano Microrreg');
INSERT INTO `tb_tipos_processo` VALUES(100001354, 'Linhas de Transporte Coletivo Intermunicipal de Pa');
INSERT INTO `tb_tipos_processo` VALUES(100001355, 'Linhas de Transporte Coletivo Metropolitano de Pas');
INSERT INTO `tb_tipos_processo` VALUES(100001356, 'Permissão de Transporte de Passageiros por Táxi Es');
INSERT INTO `tb_tipos_processo` VALUES(100001357, 'Cadastro de Permissionário e Cadastro do Motorista');
INSERT INTO `tb_tipos_processo` VALUES(100001358, 'Processo de Autorização: Autorização de Funcioname');
INSERT INTO `tb_tipos_processo` VALUES(100001359, 'Processo de Autorização: Mudança de denominação de');
INSERT INTO `tb_tipos_processo` VALUES(100001360, 'Processo de Autorização: Autorização de Funcioname');
INSERT INTO `tb_tipos_processo` VALUES(100001361, 'RH: SEF CONTRIBUIÇÃO PREVIDENCIÁRIA');
INSERT INTO `tb_tipos_processo` VALUES(100001362, 'SEAPA - Solicitação de Doação/Emenda Parlamentar');
INSERT INTO `tb_tipos_processo` VALUES(100001363, 'LEMG - Disponibilidade de Sistema');
INSERT INTO `tb_tipos_processo` VALUES(100001364, 'LEMG - Documentação de Habilitação');
INSERT INTO `tb_tipos_processo` VALUES(100001365, 'LEMG - Documentação de Premiados');
INSERT INTO `tb_tipos_processo` VALUES(100001366, 'LEMG - Fundo de Premiação');
INSERT INTO `tb_tipos_processo` VALUES(100001367, 'LEMG - Fundo de Marketing');
INSERT INTO `tb_tipos_processo` VALUES(100001368, 'RH: EPPGG - Movimentação e Exercício');
INSERT INTO `tb_tipos_processo` VALUES(100001369, 'RH: Concessão de Vale Transporte');
INSERT INTO `tb_tipos_processo` VALUES(100001370, 'Alienação de Material Permanente e de Consumo: Lei');
INSERT INTO `tb_tipos_processo` VALUES(100001371, 'Alienação de Material Permanente e de Consumo: Ces');
INSERT INTO `tb_tipos_processo` VALUES(100001372, 'Gestão Técnica de Projetos Ambientais: Áreas Conta');
INSERT INTO `tb_tipos_processo` VALUES(100001373, 'FEAM - Gestão Técnica de Projetos Ambientais: Área');
INSERT INTO `tb_tipos_processo` VALUES(100001374, 'Processo Judicial - Teste Psicológico');
INSERT INTO `tb_tipos_processo` VALUES(100001375, 'Patrimônio Imobiliário: Regularização de Imoveis -');
INSERT INTO `tb_tipos_processo` VALUES(100001376, 'FAPEMIG - Prestação de Contas Financeira');
INSERT INTO `tb_tipos_processo` VALUES(100001377, 'Produção de Medicamento');
INSERT INTO `tb_tipos_processo` VALUES(100001378, 'PRODEMGE: Prova de Conceito de Plataforma de Compu');
INSERT INTO `tb_tipos_processo` VALUES(100001379, 'Celebração de Contrato de Gestão');
INSERT INTO `tb_tipos_processo` VALUES(100001380, 'Qualificação de Organizações Sociais (OS)');
INSERT INTO `tb_tipos_processo` VALUES(100001381, 'SETOP: Certificado PMQP-H');
INSERT INTO `tb_tipos_processo` VALUES(100001382, 'Incorporação: Mercadorias Apreendidas e Abandonada');
INSERT INTO `tb_tipos_processo` VALUES(100001383, 'SEF- PMPF  Rações Secas Tipo PET');
INSERT INTO `tb_tipos_processo` VALUES(100001385, 'Prestação de Contas Anual para o TCEMG - Entidade ');
INSERT INTO `tb_tipos_processo` VALUES(100001386, 'Prestação de Contas Anual para o TCEMG - Fundos Es');
INSERT INTO `tb_tipos_processo` VALUES(100001387, 'Prestação de Contas Anual para o TCEMG - Empresas ');
INSERT INTO `tb_tipos_processo` VALUES(100001388, 'FAPEMIG - Prestação de Contas Técnica-Científica');
INSERT INTO `tb_tipos_processo` VALUES(100001389, 'Gestão de TIC: Serviço de Transmissão de Dados, Vo');
INSERT INTO `tb_tipos_processo` VALUES(100001390, 'LEMG - Publicidade');
INSERT INTO `tb_tipos_processo` VALUES(100001391, 'LEMG - Plano de Jogo');
INSERT INTO `tb_tipos_processo` VALUES(100001392, 'LEMG - Teleatendimento');
INSERT INTO `tb_tipos_processo` VALUES(100001393, 'LEMG - Prêmio Extra TOTOLOT');
INSERT INTO `tb_tipos_processo` VALUES(100001394, 'Contabilidade: Adiantamento e Empréstimo a Servido');
INSERT INTO `tb_tipos_processo` VALUES(100001395, 'RH: Assédio Moral');
INSERT INTO `tb_tipos_processo` VALUES(100001396, 'LEMG - IRRF');
INSERT INTO `tb_tipos_processo` VALUES(100001397, 'LEMG - Processos Diversos');
INSERT INTO `tb_tipos_processo` VALUES(100001398, 'Previdência. Assistência. Seguridade Social: Polít');
INSERT INTO `tb_tipos_processo` VALUES(100001399, 'Previdência. Assistência. Seguridade Social: Benef');
INSERT INTO `tb_tipos_processo` VALUES(100001400, 'Previdência. Assistência. Seguridade Social: Benef');
INSERT INTO `tb_tipos_processo` VALUES(100001401, 'Previdência. Assistência. Seguridade Social: Benefícios: Aposentadoria');
INSERT INTO `tb_tipos_processo` VALUES(100001402, 'Previdência. Assistência. Seguridade Social: Aposentadoria - Apuração de Tempo de Serviço');
INSERT INTO `tb_tipos_processo` VALUES(100001403, 'Previdência. Assistência. Seguridade Social: Aposentadoria - Pensões');
INSERT INTO `tb_tipos_processo` VALUES(100001404, 'Previdência. Assistência. Seguridade Social: Apose');
INSERT INTO `tb_tipos_processo` VALUES(100001405, 'Previdência. Assistência. Seguridade Social: Apose');
INSERT INTO `tb_tipos_processo` VALUES(100001406, 'Modernização Institucional: Estudo de Viabilidade ');
INSERT INTO `tb_tipos_processo` VALUES(100001407, 'RH: Indenização do saldo de férias regulamentares');
INSERT INTO `tb_tipos_processo` VALUES(100001408, 'DEER - Declaração de Veículo Escolar');
INSERT INTO `tb_tipos_processo` VALUES(100001409, 'DEER - Declaração de Cadastro e Fretamento');
INSERT INTO `tb_tipos_processo` VALUES(100001410, 'RH: Substituição Temporária (CBMMG)');
INSERT INTO `tb_tipos_processo` VALUES(100001411, 'Contabilidade: Balanço Geral');
INSERT INTO `tb_tipos_processo` VALUES(100001412, 'Destinação de Documentos: Análise. Avaliação. Seleção');
INSERT INTO `tb_tipos_processo` VALUES(100001413, 'Previdência. Assistência. Seguridade Social: Assis');
INSERT INTO `tb_tipos_processo` VALUES(100001414, 'Previdência. Assistência. Seguridade Social: Benef');
INSERT INTO `tb_tipos_processo` VALUES(100001415, 'Funcionamento Escolar: Estudos das Normas da Educa');
INSERT INTO `tb_tipos_processo` VALUES(100001416, 'Regularidade de Funcionamento da Escola: Validação');
INSERT INTO `tb_tipos_processo` VALUES(100001417, 'SEPLAG - Assesoria de Relações Sindicais - ARS');
INSERT INTO `tb_tipos_processo` VALUES(100001418, 'DEER - Movimentação Cadastral Fretamento - Regiona');
INSERT INTO `tb_tipos_processo` VALUES(100001419, 'DEER - Movimentação Cadastral Fretamento - Regiona');
INSERT INTO `tb_tipos_processo` VALUES(100001420, 'DEER - Movimentação Cadastral Fretamento - Regiona');
INSERT INTO `tb_tipos_processo` VALUES(100001421, 'DEER - Movimentação Cadastral Fretamento - Regiona');
INSERT INTO `tb_tipos_processo` VALUES(100001422, 'DEER - Movimentação Cadastral Fretamento - Regiona');
INSERT INTO `tb_tipos_processo` VALUES(100001423, 'DEER - Movimentação Cadastral Fretamento - Regiona');
INSERT INTO `tb_tipos_processo` VALUES(100001424, 'DEER - Movimentação Cadastral Fretamento - Regiona');
INSERT INTO `tb_tipos_processo` VALUES(100001425, 'DEER - Movimentação Cadastral Fretamento - Regiona');
INSERT INTO `tb_tipos_processo` VALUES(100001427, 'DEER - Movimentação Cadastral Fretamento - Regiona');
INSERT INTO `tb_tipos_processo` VALUES(100001428, 'DEER - Movimentação Cadastral Fretamento - Regiona');
INSERT INTO `tb_tipos_processo` VALUES(100001429, 'DEER - Movimentação Cadastral Fretamento - Regiona');
INSERT INTO `tb_tipos_processo` VALUES(100001430, 'DEER - Movimentação Cadastral Fretamento - Interior');
INSERT INTO `tb_tipos_processo` VALUES(100001431, 'DEER - Movimentação Cadastral Fretamento - Regiona');
INSERT INTO `tb_tipos_processo` VALUES(100001432, 'DEER - Movimentação Cadastral Fretamento - Regiona');
INSERT INTO `tb_tipos_processo` VALUES(100001433, 'Gestão de Atas de Registro de Preços: Processo Adm');
INSERT INTO `tb_tipos_processo` VALUES(100001434, 'DEER - Movimentação Cadastral Fretamento - Regiona');
INSERT INTO `tb_tipos_processo` VALUES(100001435, 'DEER - Movimentação Cadastral Fretamento - Regional Uberlândia');
INSERT INTO `tb_tipos_processo` VALUES(100001436, 'IEF: Criador Amador de Passeriformes da Fauna Silv');
INSERT INTO `tb_tipos_processo` VALUES(100001437, 'DEER - Movimentação Cadastral Fretamento - Regiona');
INSERT INTO `tb_tipos_processo` VALUES(100001438, 'Eventos: Promovidos por outras Instituições (Congressos. Conferências, Treinamentos, Workshops...)');
INSERT INTO `tb_tipos_processo` VALUES(100001439, 'Eventos: Promovidos pelo órgão (Solenidades. Comem');
INSERT INTO `tb_tipos_processo` VALUES(100001440, 'Eventos: Promovidos por outras instituições (Solen');
INSERT INTO `tb_tipos_processo` VALUES(100001441, 'RH: Captação e Seleção - HEMOMINAS');
INSERT INTO `tb_tipos_processo` VALUES(100001442, 'Doação');
INSERT INTO `tb_tipos_processo` VALUES(100001443, 'Aquisição - Doação Manifestação de Interesse');
INSERT INTO `tb_tipos_processo` VALUES(100001444, 'Aquisição - Doação Chamamento Público');
INSERT INTO `tb_tipos_processo` VALUES(100001445, 'DEER - Movimentação Cadastral Fretamento - Regiona');
INSERT INTO `tb_tipos_processo` VALUES(100001446, 'DEER - Movimentação Cadastral Fretamento - Regiona');
INSERT INTO `tb_tipos_processo` VALUES(100001447, 'DEER - Movimentação Cadastral Fretamento - Regiona');
INSERT INTO `tb_tipos_processo` VALUES(100001448, 'DEER - Movimentação Cadastral Fretamento - Regiona');
INSERT INTO `tb_tipos_processo` VALUES(100001449, 'DEER - Movimentação Cadastral Fretamento - Regiona');
INSERT INTO `tb_tipos_processo` VALUES(100001450, 'DEER - Movimentação Cadastral Fretamento - Regiona');
INSERT INTO `tb_tipos_processo` VALUES(100001451, 'DEER - Movimentação Cadastral Fretamento - Regiona');
INSERT INTO `tb_tipos_processo` VALUES(100001452, 'Regularização e Titulação Fundiária Rural');
INSERT INTO `tb_tipos_processo` VALUES(100001453, 'Gestão de TIC: Serviço Telefônico');
INSERT INTO `tb_tipos_processo` VALUES(100001454, 'SEE_ Gestão de Convênios - Entre entidades Governa');
INSERT INTO `tb_tipos_processo` VALUES(100001455, 'Alienação: Apreensões e Abandono');
INSERT INTO `tb_tipos_processo` VALUES(100001456, 'RH: Movimentação Interna de Servidor - HEMOMINAS');
INSERT INTO `tb_tipos_processo` VALUES(100001457, 'IGAM - Pedido de Restituição de Indébito Tributári');
INSERT INTO `tb_tipos_processo` VALUES(100001458, 'IEF - Pedido de Restituição de Indébito Tributário');
INSERT INTO `tb_tipos_processo` VALUES(100001459, 'SEMAD - Pedido de Restituição de Indébito Tributár');
INSERT INTO `tb_tipos_processo` VALUES(100001460, 'FEAM - Pedido de Restituição de Indébito Tributári');
INSERT INTO `tb_tipos_processo` VALUES(100001461, 'DEER - Infração de Trânsito');
INSERT INTO `tb_tipos_processo` VALUES(100001462, 'DEER - Infração de Trânsito: Identificação  do Con');
INSERT INTO `tb_tipos_processo` VALUES(100001463, 'DEER - Infração de Transito: Identificação do real');
INSERT INTO `tb_tipos_processo` VALUES(100001464, 'DEER - Infração de Transito: Recurso CETRAN');
INSERT INTO `tb_tipos_processo` VALUES(100001465, 'DEER - Infração de Transito: Defesa da Autuação');
INSERT INTO `tb_tipos_processo` VALUES(100001466, 'DEER - Infração de Transito: Recurso JARI/DEER-MG');
INSERT INTO `tb_tipos_processo` VALUES(100001468, 'Previdência. Assistência. Seguridade Social: Avali');
INSERT INTO `tb_tipos_processo` VALUES(100001469, 'Previdência. Assistência. Seguridade Social: Benef');
INSERT INTO `tb_tipos_processo` VALUES(100001470, 'Contratos de Gestão: Avaliação de Gestão e Resulta');
INSERT INTO `tb_tipos_processo` VALUES(100001471, 'Previdência. Assistência. Seguridade Social: Outro');
INSERT INTO `tb_tipos_processo` VALUES(100001472, 'Previdência. Assistência. Seguridade Social: Pedidos, Oferecimentos e Informações');
INSERT INTO `tb_tipos_processo` VALUES(100001473, 'JUCEMG: Redesim MG');
INSERT INTO `tb_tipos_processo` VALUES(100001474, 'JUCEMG: Sala Mineira do Empreendedor');
INSERT INTO `tb_tipos_processo` VALUES(100001475, 'UNIMONTES -  Acadêmico - Requerimento Genérico');
INSERT INTO `tb_tipos_processo` VALUES(100001476, 'Informações de Doadores e Pacientes');
INSERT INTO `tb_tipos_processo` VALUES(100001477, 'SES: Convênio com Municípios (Recursos Federais)');
INSERT INTO `tb_tipos_processo` VALUES(100001478, 'SES: Convênio com Entidade Privada Sem Fins Lucrat');
INSERT INTO `tb_tipos_processo` VALUES(100001479, 'SES: Convênio com Municípios (Recursos Estaduais)');
INSERT INTO `tb_tipos_processo` VALUES(100001480, 'SES: Convênio com Entidade Privada Sem Fins Lucrat');
INSERT INTO `tb_tipos_processo` VALUES(100001482, 'IGAM - Usos Isentos de Outorga');
INSERT INTO `tb_tipos_processo` VALUES(100001483, 'RH - Outros Direitos. Obrigações. Vantagens. Concessões: Auxílios');
INSERT INTO `tb_tipos_processo` VALUES(100001484, 'Processo Judicial: Prontuário');
INSERT INTO `tb_tipos_processo` VALUES(100001485, 'Inscrição Processo Eleição Copam 2020/2022');
INSERT INTO `tb_tipos_processo` VALUES(100001486, 'SEINFRA: Transporte Intermunicipal - Empresa');
INSERT INTO `tb_tipos_processo` VALUES(100001487, 'SEINFRA: Transporte Intermunicipal - Linhas');
INSERT INTO `tb_tipos_processo` VALUES(100001488, 'SEINFRA: Transporte Intermunicipal - Veículos');
INSERT INTO `tb_tipos_processo` VALUES(100001489, 'SEINFRA: Transporte Intermunicipal - Outros Requer');
INSERT INTO `tb_tipos_processo` VALUES(100001490, 'PMMG: Emissão de Certidões');
INSERT INTO `tb_tipos_processo` VALUES(100001491, 'PMMG: Pasta Funcional Física');
INSERT INTO `tb_tipos_processo` VALUES(100001492, 'RH: Auditoria da Folha de Pagamento: Recuperação de Valores');
INSERT INTO `tb_tipos_processo` VALUES(100001493, 'RH: Auditoria da Folha de Pagamento: Retenções de Pagamento');
INSERT INTO `tb_tipos_processo` VALUES(100003315, 'RH: Reopção Semad');
INSERT INTO `tb_tipos_processo` VALUES(100003465, 'RH: Atribuição e Revogação de GFPE');
INSERT INTO `tb_tipos_processo` VALUES(100003765, 'RH: Afastamento aguardando transferência para inatividade');
INSERT INTO `tb_tipos_processo` VALUES(100004815, 'RH: Monitor Individual – Dosímetro');
INSERT INTO `tb_tipos_processo` VALUES(100004965, 'RH: Jornada de Trabalho - IPSEMG');
INSERT INTO `tb_tipos_processo` VALUES(100005115, 'RH: Jornada de Trabalho - Médico - IPSEMG');
INSERT INTO `tb_tipos_processo` VALUES(100005265, 'RH: Dispensa de Ponto Para Participação em Eventos');
INSERT INTO `tb_tipos_processo` VALUES(100005415, 'RH: Retorno Antecipado de Afastamento');
INSERT INTO `tb_tipos_processo` VALUES(100006765, 'RH: Gratificação de Incentivo a Produtividade (GIPPEA)');
INSERT INTO `tb_tipos_processo` VALUES(100006915, 'Trâmite de Processo Físico e/ou Objeto');
INSERT INTO `tb_tipos_processo` VALUES(100007815, 'RH: Atribuição ou Dispensa de Gratificação Temporária Estratégica (GTEI)');
INSERT INTO `tb_tipos_processo` VALUES(100011115, 'RH: Pensão Alimento');
INSERT INTO `tb_tipos_processo` VALUES(100012465, 'RH: Concessão de Jornada Estendida - UNIMONTES');
INSERT INTO `tb_tipos_processo` VALUES(100012615, 'RH: Certificado de Avaliação de Títulos - CAT');
INSERT INTO `tb_tipos_processo` VALUES(100013065, 'RH: Requisição de Teletrabalho');
INSERT INTO `tb_tipos_processo` VALUES(100015015, 'RH: Pasta Funcional - Migração Ponto Digital');
INSERT INTO `tb_tipos_processo` VALUES(100021166, 'RH: Afastamento COVID-19');
INSERT INTO `tb_tipos_processo` VALUES(100022366, 'RH: Licença por motivo de doença em pessoa na família');
INSERT INTO `tb_tipos_processo` VALUES(100023865, 'RH - Declaração de Bens e Valores - IPSEMG');
INSERT INTO `tb_tipos_processo` VALUES(100024165, 'RH: Prontuário Médico');
INSERT INTO `tb_tipos_processo` VALUES(100025215, 'IPSEMG - Inclusão de Dependente à Assistência Saúde');
INSERT INTO `tb_tipos_processo` VALUES(100025216, 'IPSEMG - Exclusão de Dependentes à Assistência Saúde');
INSERT INTO `tb_tipos_processo` VALUES(100025665, 'RH: Redução de Jornada de Trabalho');
INSERT INTO `tb_tipos_processo` VALUES(100025965, 'RH: Perícia Médica: Retificação de Licença');
INSERT INTO `tb_tipos_processo` VALUES(100025966, 'RH: Perícia Médica: Justificativa de ausência em junta médica');
INSERT INTO `tb_tipos_processo` VALUES(100025967, 'RH: Perícia Médica: Fotocópia');
INSERT INTO `tb_tipos_processo` VALUES(100025968, 'RH: Perícia Médica: Outros');
INSERT INTO `tb_tipos_processo` VALUES(100026115, 'RH: Perícia Médica: Isenção de Imposto de Renda');
INSERT INTO `tb_tipos_processo` VALUES(100026116, 'RH: Perícia Médica: Ajustamento Funcional');
INSERT INTO `tb_tipos_processo` VALUES(100026117, 'RH: Perícia Médica: Recurso');
INSERT INTO `tb_tipos_processo` VALUES(100026865, 'RH: Perícia Médica - BIM');
INSERT INTO `tb_tipos_processo` VALUES(100027015, 'RH: Perícia Médica: Informação de Licença');
INSERT INTO `tb_tipos_processo` VALUES(100027165, 'RH: Perícia Médica: Entrega de Documentos');
INSERT INTO `tb_tipos_processo` VALUES(100027315, 'RH: Perícia Médica: CAT');
INSERT INTO `tb_tipos_processo` VALUES(100027615, 'RH: Perícia Médica: Licença para Tratamento de Saúde');
INSERT INTO `tb_tipos_processo` VALUES(100028815, 'RH - Contrato Administrativo - Celebração');
INSERT INTO `tb_tipos_processo` VALUES(100029265, 'RH: Pasta Funcional Física');
INSERT INTO `tb_tipos_processo` VALUES(100030315, 'RH: Atendimento Sociofuncional');
INSERT INTO `tb_tipos_processo` VALUES(100035715, 'RH: Serviço eleitoral com geração de folga compensativa');

INSERT INTO `tb_usuarios` VALUES(1, 1501, 3, 'Carga Inicial', 'administrador@planejamento.mg.gov.br', '999999', '99999999999', '7b48a10755de57fd1e60a653c33f7d6c005d3862260b86ca179d8d278a33bae8d3af96228314bc71f9206763801a84e9f16ff5ecac76469993e4a3e8539e0256bhcxARkqQ9PQnd0V2UnjDWiqyhyoMnheirAvQRfUq/M=', NULL, '999999999', 990000999, NULL, NULL, '2021-06-18 13:29:37', '0', 0, '0');
